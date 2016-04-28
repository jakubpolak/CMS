<?php

namespace AppBundle\Service;

use AppBundle\Entity\Translation;
use AppBundle\Entity\TranslationMapper;
use AppBundle\Helper\DQLHelper;
use AppBundle\Repository\TranslationMapperRepository;
use AppBundle\Repository\TranslationRepository;
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
     * @var LanguageService
     */
    private $languageService;

    /**
     * @var CacheService
     */
    private $cacheService;

    /**
     * @var TranslationRepository
     */
    private $translationRepository;

    /**
     * @var TranslationMapperRepository
     */
    private $translationMapperRepository;

    /**
     * @var DQLHelper
     */
    private $DQLHelper;

    /**
     * @var array
     */
    private $config;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager entity manager
     * @param LanguageService $languageService language service
     * @param CacheService $cacheService cache service
     * @param $DQLHelper $DQLHelper DQL helper
     * @param array $config
     */
    public function __construct(
        EntityManager $entityManager,
        LanguageService $languageService,
        CacheService $cacheService,
        DQLHelper $DQLHelper,
        array $config
    ) {
        $this->em = $entityManager;
        $this->languageService = $languageService;
        $this->cacheService = $cacheService;
        $this->DQLHelper = $DQLHelper;
        $this->config = $config;
        $this->translationRepository = $entityManager->getRepository('AppBundle:Translation');
        $this->translationMapperRepository = $entityManager->getRepository('AppBundle:TranslationMapper');
    }

    /**
     * Synchronize translation tables with the rest of database and translation files
     * with translation tables.
     */
    public function synchronize() {
        $entities = $this->config['entities'];

        foreach ($entities as $entityName => $entityAttributes) {
            $namesOfEntityAttributes = array_keys($entityAttributes);

            $this->removeInvalidTranslationMappers($entityName, $namesOfEntityAttributes);
            $this->removeInvalidTranslations();
            $this->updateExistingTranslationMappers($entityName, $namesOfEntityAttributes);
            $this->createTranslationMappers($entityName, $namesOfEntityAttributes, $entityAttributes);
        }
    }

    /**
     * Get count of pages.
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
    public function updateExistingTranslationMappers(string $entity, array $namesOfEntityAttributes) {
        $idsOfEntities = $this->translationMapperRepository->getEntityIdByEntity($entity);

        if (count($idsOfEntities) === 0) {
            return;
        }

        $idsOfEntitiesVector = $this->DQLHelper->convertToVector($idsOfEntities, 'entityId');
        $idsOfEntitiesDQL = $this->DQLHelper->convertToString($idsOfEntitiesVector);
        $namesOfAttributesWithAliasDQL = $this->DQLHelper->getAttributesWithAliasDQL($namesOfEntityAttributes);

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
    public function createTranslationMappers(string $entity, array $namesOfEntityAttributes, array $entityAttributes) {
        $idsOfEntities = $this->translationMapperRepository->getEntityIdByEntity($entity);
        $namesOfAttributesWithAliasDQL = $this->DQLHelper->getAttributesWithAliasDQL($namesOfEntityAttributes);
        $defaultLanguage = $this->languageService->getDefaultLanguage();

        $dql = "SELECT $namesOfAttributesWithAliasDQL FROM AppBundle:$entity e";
        if (count($idsOfEntities) !== 0) {
            $idsOfEntitiesVector = $this->DQLHelper->convertToVector($idsOfEntities, 'entityId');
            $idsOfEntitiesDQL = $this->DQLHelper->convertToString($idsOfEntitiesVector);

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
                    ->setAttributeType($attributeType)
                    ->setLanguage($defaultLanguage)
                ;
                $this->em->persist($translationMapper);
                $this->persistTranslations($translationMapper);
                $this->em->flush();
            }
        }
    }
    
    /**
     * Get translation groups for single entity.
     * 
     * @param string $entity entity
     * @param int $entityId entity id     
     * @return array
     */
    public function getGroupsForSingleEntity(string $entity, int $entityId): array {
        $groups = $this->translationRepository->getByLanguageIdAndEntityAndEntityId($entity, $entityId, true);
        $displayNames = $this->config['display_names'];

        foreach ($groups as &$group) {
            $group['attributeDisplayName'] = $displayNames[$group['attribute']];
        }

        return $groups;
    }

    /**
     * Get translations limited by first result and max results.
     * 
     * @param int $firstResult first result
     * @param int $maxResults max results
     * @return array
     */
    public function getPagination(int $firstResult, int $maxResults): array {
        $defaultLanguage = $this->languageService->getDefaultLanguage();
        $entries = $this->translationRepository
            ->getLimitedByLanguageId($defaultLanguage->getId(), --$firstResult * $maxResults, $maxResults);

        $result = [];
        foreach ($entries as $entry) {
            $translationMapper = $entry->getTranslationMapper();

            $entities = $this->translationRepository->getByLanguageIdAndEntityAndEntityId(
                $defaultLanguage->getId(),
                $translationMapper->getEntity(),
                $translationMapper->getEntityId(),
                true
            );

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
     * Create translations.
     *
     * @param TranslationMapper $translationMapper translation mapper
     */
    private function persistTranslations(TranslationMapper $translationMapper) {
        $languages = $this->languageService->getAll();
        $defaultLanguage = $this->languageService->getDefaultLanguage();

        foreach ($languages as $language) {
            $translation = new Translation();
            $translation->setLanguage($language);
            $translation->setTranslationMapper($translationMapper);

            if ($language === $defaultLanguage) {
                $translation->setContent($translationMapper->getAttributeContent());
            }

            $this->em->persist($translation);
        }
    }

    /**
     * Remove invalid translations.
     */
    private function removeInvalidTranslations() {
        $languages = $this->languageService->getAll();
        $ids = [];

        foreach ($languages as $language) {
            $ids[] = $language->getId();
        }

        $idsDQL = $this->DQLHelper->convertToString($ids, ',');
        $this->translationRepository->delete($idsDQL);
    }

    /**
     * Remove entries that do not exist and remove non-existing attributes of entities.
     *
     * @param string $entityName entity name
     * @param array $namesOfEntityAttributes names of entity attributes
     */
    private function removeInvalidTranslationMappers(string $entityName, array $namesOfEntityAttributes) {
        $idsOfEntities = $this->DQLHelper->getIds($this->em, $entityName);
        $idsOfEntitiesVector = $this->DQLHelper->convertToVector($idsOfEntities, 'id');
        $idsOfEntitiesDQL = $this->DQLHelper->convertToString($idsOfEntitiesVector, ',');
        $namesOfAttributesWrappedDQL = $this->DQLHelper->convertToString($namesOfEntityAttributes, ',', "'");
        $this->translationMapperRepository->delete($idsOfEntitiesDQL, $namesOfAttributesWrappedDQL, $entityName);
    }
}
