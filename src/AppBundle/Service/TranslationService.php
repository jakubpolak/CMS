<?php

namespace AppBundle\Service;

use AppBundle\Entity\TranslationMapper;
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
    }

    public function synchronize() {
        $entities = $this->config['entities'];

        foreach ($entities as $entityName => $entityAttributes) {
            $namesOfEntityAttributes = array_keys($entityAttributes);

            $this->removeInvalidEntries($entityName, $namesOfEntityAttributes);
            // TODO: Update existing entries
            $this->createNewEntries($entityName, $namesOfEntityAttributes, $entityAttributes);
        }
    }

    private function createNewEntries(string $entityName, array $namesOfEntityAttributes, array $entityAttributes) {
        $namesOfAttributesWithAliasDQL = $this->getAttributesWithAliasDQL($namesOfEntityAttributes);

        $valuesOfEntities = $this->em
            ->createQuery("SELECT $namesOfAttributesWithAliasDQL FROM AppBundle:$entityName e")
            ->getArrayResult();

        foreach ($valuesOfEntities as $valuesOfAttributes) {
            foreach ($valuesOfAttributes as $attributeName => $attributeValue) {
                if ($attributeName === 'id') { continue; }
                $attributeType = $entityAttributes[$attributeName];
                $translationMapper = new TranslationMapper();
                $translationMapper->setEntity($entityName)
                    ->setEntityId($valuesOfAttributes['id'])
                    ->setAttribute($attributeName)
                    ->setAttributeCurrentContent($attributeValue)
                    ->setAttributeOriginalContent($attributeValue)
                    ->setAttributeType($attributeType);
                $this->em->persist($translationMapper);
            }
        }

        $this->em->flush();
    }

    /**
     * Remove invalid entries.
     *
     * @param string $entityName entity name
     * @param array $namesOfEntityAttributes names of entity attributes
     */
    private function removeInvalidEntries(string $entityName, array $namesOfEntityAttributes) {
        $idsOfEntities = $this->em
            ->createQuery("SELECT e.id FROM AppBundle:$entityName e")
            ->getArrayResult();
        $idsOfEntities = $this->convertToOneDimension($idsOfEntities, 'id');
        $idsOfEntitiesDQL = $this->convertToString($idsOfEntities, ',');
        $namesOfAttributesWrappedDQL = $this->convertToString($namesOfEntityAttributes, ',', "'");

        $dql = "
            DELETE
                FROM AppBundle:TranslationMapper tm
            WHERE
                (tm.entityId NOT IN($idsOfEntitiesDQL) OR tm.attribute NOT IN($namesOfAttributesWrappedDQL))
                AND tm.entity = '{$entityName}'";

        $this->em->createQuery($dql)->execute();
    }

    /**
     * Convert two dimensional array to one dimensional array.
     *
     * @param array $twoDimension two dimensional array
     * @param string $key array key of second dimension of two dimensional array
     * @return array
     */
    private function convertToOneDimension(array $twoDimension, string $key): array {
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
        $length = strlen($result);
        return substr($result, 0, $length - 1);
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
        $length = strlen($result);
        return substr($result, 0, $length - 1);
    }
}