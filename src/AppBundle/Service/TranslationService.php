<?php

namespace AppBundle\Service;

use AppBundle\Entity\TranslationMapper;
use AppBundle\Repository\TranslationMapperRepository;
use Doctrine\ORM\EntityManager;

/**
 * Translation service.
 *
 * @author Jakub Polák, Jana Poláková
 */
class TranslationService {
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var CacheService
     */
    private $cacheService;

    /**
     * @var array
     */
    private $config;

    /**
     * @var TranslationMapperRepository
     */
    private $translationMapperRepository;

    /**
     * Constructor.
     *
     * @param EntityManager $em entity manager
     * @param CacheService $cacheService cache service
     * @param array $config
     */
    public function __construct(EntityManager $em, CacheService $cacheService, array $config) {
        $this->em = $em;
        $this->cacheService = $cacheService;
        $this->config = $config;
        $this->translationMapperRepository = $em->getRepository('AppBundle:TranslationMapper');
    }

    public function synchronize() {
        $entities = $this->config['entities'];

        foreach ($entities as $entityName => $entityAttributes) {
            $namesOfEntityAttributes = array_keys($entityAttributes);

            $this->removeInvalidEntries($entityName, $namesOfEntityAttributes);
            $this->updateExistingEntries($entityName, $namesOfEntityAttributes);
            $this->createNewEntries($entityName, $namesOfEntityAttributes, $entityAttributes);
        }
    }

    /**
     * Update existing entries.
     *
     * @param string $entity entity name
     * @param array $namesOfEntityAttributes names of entity attributes
     */
    private function updateExistingEntries(string $entity, array $namesOfEntityAttributes) {
        $idsOfEntities = $this->translationMapperRepository->getEntityIds($entity);
        $idsOfEntitiesVector = $this->convertToVector($idsOfEntities, 'entityId');
        $idsOfEntitiesDQL = $this->convertToString($idsOfEntitiesVector);
        $namesOfAttributesWithAliasDQL = $this->getAttributesWithAliasDQL($namesOfEntityAttributes);

        $valuesOfEntities = $this->em
            ->createQuery("SELECT {$namesOfAttributesWithAliasDQL} FROM AppBundle:$entity e WHERE e.id IN ($idsOfEntitiesDQL)")
            ->getArrayResult();

        foreach ($valuesOfEntities as $attributes) {
            foreach ($attributes as $name => $content) {
                if ($name === 'id') {
                    continue;
                }

                $this->translationMapperRepository->updateAttributeContent($content, $name, (int) $attributes['id'], $entity);
            }
        }
    }

    /**
     * Create new entries.
     *
     * @param string $entity entity name
     * @param array $namesOfEntityAttributes names of entity attributes
     * @param array $entityAttributes entity attributes
     */
    private function createNewEntries(string $entity, array $namesOfEntityAttributes, array $entityAttributes) {
        $idsOfEntities = $this->translationMapperRepository->getEntityIds($entity);
        $namesOfAttributesWithAliasDQL = $this->getAttributesWithAliasDQL($namesOfEntityAttributes);

        $dql = "SELECT $namesOfAttributesWithAliasDQL FROM AppBundle:$entity e";
        if (count($idsOfEntities) !== 0) {
            $idsOfEntitiesVector = $this->convertToVector($idsOfEntities, 'entityId');
            $idsOfEntitiesDQL = $this->convertToString($idsOfEntitiesVector);

            $dql = "SELECT $namesOfAttributesWithAliasDQL FROM AppBundle:$entity e WHERE e.id NOT IN($idsOfEntitiesDQL)";
        }

        $valuesOfEntities = $this->em->createQuery($dql)->getArrayResult();

        foreach ($valuesOfEntities as $valuesOfAttributes) {
            foreach ($valuesOfAttributes as $attributeName => $attributeValue) {
                if ($attributeName === 'id') {
                    continue;
                }
                $attributeType = $entityAttributes[$attributeName];
                $translationMapper = new TranslationMapper();
                $translationMapper->setEntity($entity)
                    ->setEntityId($valuesOfAttributes['id'])
                    ->setAttribute($attributeName)
                    ->setAttributeContent($attributeValue)
                    ->setAttributeType($attributeType);
                $this->em->persist($translationMapper);
            }
        }

        $this->em->flush();
    }

    /**
     * Get ids of entities by entity name.
     *
     * @param string $entityName entity name
     * @return array
     */
    private function getIds(string $entityName): array {
        return $this->em->createQuery("SELECT e.id FROM AppBundle:$entityName e")->getArrayResult();
    }

    /**
     * Remove entries that do not exist and remove non-existing attributes of entities.
     *
     * @param string $entityName entity name
     * @param array $namesOfEntityAttributes names of entity attributes
     */
    private function removeInvalidEntries(string $entityName, array $namesOfEntityAttributes) {
        $idsOfEntities = $this->getIds($entityName);
        $idsOfEntitiesVector = $this->convertToVector($idsOfEntities, 'id');
        $idsOfEntitiesDQL = $this->convertToString($idsOfEntitiesVector, ',');
        $namesOfAttributesWrappedDQL = $this->convertToString($namesOfEntityAttributes, ',', "'");
        $this->translationMapperRepository->delete($idsOfEntitiesDQL, $namesOfAttributesWrappedDQL, $entityName);
    }

    /**
     * Get attributes for DQL.
     *
     * @param array $namesOfAttributes names of entity attributes
     * @return string
     */
    private function getAttributesWithAliasDQL(array $namesOfAttributes): string {
        $result = 'e.id,';
        foreach ($namesOfAttributes as $attribute) {
            $result .= "e.{$attribute},";
        }
        return substr($result, 0, - 1);
    }

    /**
     * Convert two dimensional array to one dimensional array.
     *
     * @param array $twoDimension two dimensional array
     * @param string $key array key of second dimension of two dimensional array
     * @return array
     */
    private function convertToVector(array $twoDimension, string $key): array {
        $oneDimension = array();
        foreach ($twoDimension as $entry) {
            $oneDimension[] = $entry[$key];
        }
        return $oneDimension;
    }

    /**
     * Convert array to a string.
     *
     * @param array $array array of values to be converted to a string
     * @param string $delimiter string to delimit array values by
     * @param string $wrap string to wrap array values in
     * @return string
     */
    private function convertToString(array $array, string $delimiter = ',', string $wrap = ''): string {
        $result = '';
        foreach ($array as $value) {
            $result .= "{$wrap}{$value}{$wrap}{$delimiter}";
        }
        return substr($result, 0, - 1);
    }
}
