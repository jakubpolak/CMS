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

    /**
     * Synchronize translation tables with the rest of database and translation files
     * with translation tables.
     */
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
     * Get entity groups.
     * 
     * @param string $entity entity name
     * @param int $entityId entity id
     * @return array
     */
    public function getEntityGroups(string $entity, int $entityId): array {
        $entityGroups = $this->translationMapperRepository->getByEntityAndEntityId($entity, $entityId, true);
        $displayNames = $this->config['display_names'];
        
        foreach ($entityGroups as &$entityGroup) {
            $entityGroup['attributeDisplayName'] = $displayNames[$entityGroup['attribute']];
        }

        return $entityGroups;
    }

    /**
     * Get entries for paginated list of results.
     *
     * @param int $firstResult first result
     * @param int $maxResults max results
     * @return array
     */
    public function getPagination(int $firstResult, int $maxResults): array {
        $entries = $this->translationMapperRepository
            ->getLimitedAndGroupedByEntityIdAndEntity(--$firstResult * $maxResults, $maxResults);

        $result = [];
        foreach ($entries as $entry) {
            $entities = $this->translationMapperRepository
                ->getByEntityAndEntityId($entry->getEntity(), $entry->getEntityId(), true);
            $result[] = ['entities' => $entities, 'details' => []];
        }

        $displayNames = $this->config['display_names'];

        foreach ($result as $group => &$entitiesAndDetails) {
            $entityName = $entitiesAndDetails['entities'][0]['entity'];
            $entityId = $entitiesAndDetails['entities'][0]['entityId'];
            
            $entitiesAndDetails['details']['entityDisplayName'] = $displayNames[$entityName];
            $entitiesAndDetails['details']['entity'] = $entityName;
            $entitiesAndDetails['details']['entityId'] = $entityId;

            foreach ($entitiesAndDetails['entities'] as &$entity) {
                $entity['attributeDisplayName'] = $displayNames[$entity['attribute']];
            }
        }

        return $result;
    }

    /**
     * Get count of entries for paginated list of results.
     *
     * @param int $maxResults max results
     * @return int
     */
    public function getPagesCount(int $maxResults): int {
        return ceil($this->translationMapperRepository->getCountGroupedByEntityIdAndEntity() / $maxResults);
    }

    /**
     * Update existing entries.
     *
     * @param string $entity entity name
     * @param array $namesOfEntityAttributes names of entity attributes
     */
    private function updateExistingEntries(string $entity, array $namesOfEntityAttributes) {
        $idsOfEntities = $this->translationMapperRepository->getEntityIdByEntity($entity);

        if (count($idsOfEntities) === 0) {
            return;
        }

        $idsOfEntitiesVector = $this->convertToVector($idsOfEntities, 'entityId');
        $idsOfEntitiesDQL = $this->convertToString($idsOfEntitiesVector);
        $namesOfAttributesWithAliasDQL = $this->getAttributesWithAliasDQL($namesOfEntityAttributes);

        $valuesOfEntities = $this->em
            ->createQuery("
                SELECT {$namesOfAttributesWithAliasDQL} 
                FROM AppBundle:$entity e 
                WHERE e.id IN ($idsOfEntitiesDQL)
            ")->getArrayResult();

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
        $idsOfEntities = $this->translationMapperRepository->getEntityIdByEntity($entity);
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
