<?php

namespace AppBundle\Service;

use AppBundle\Entity\Translation;
use AppBundle\Entity\TranslationMapper;
use AppBundle\Helper\DqlHelper;
use AppBundle\Repository\TranslationMapperRepository;
use AppBundle\Repository\TranslationRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Translation service.
 *
 * @author Jakub Polák, Jana Poláková
 */
class TranslationService {
    /**
     * @var EntityManager
     */
    private $entityManager;

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
     * @var DqlHelper
     */
    private $dqlHelper;

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
     * @param DqlHelper $DqlHelper dql helper
     * @param array $config translation configuration
     */
    public function __construct(
        EntityManager $entityManager,
        LanguageService $languageService,
        CacheService $cacheService,
        DqlHelper $DqlHelper,
        array $config
    ) {
        $this->entityManager = $entityManager;
        $this->languageService = $languageService;
        $this->cacheService = $cacheService;
        $this->dqlHelper = $DqlHelper;
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

        foreach ($entities as $entity => $entityAttributes) {
            $namesOfAttributes = array_keys($entityAttributes);

            $this->removeInvalidTranslationMappers($entity, $namesOfAttributes);
            $this->removeInvalidTranslations();
            $this->updateTranslationMappers($entity, $namesOfAttributes);
            $this->updateTranslations($entity);
            $this->createTranslationMappers($entity, $namesOfAttributes, $entityAttributes);
        }

        $this->cacheService->clearCache();
    }

    /**
     * Update translations.
     *
     * @param Request $request
     */
    public function updateTranslationsByRequest(Request $request) {
        $translations = $request->request->all();

        foreach ($translations as $id => $content) {
            $translation = $this->translationRepository->find($id);
            $translation->setContent($content);
            $language = $translation->getLanguage();

            if ($language->getIsDefault() === true) {
                $translationMapper = $translation->getTranslationMapper();
                $entityName = $translationMapper->getEntity();
                $attributeName = $translationMapper->getAttribute();
                $entityId = $translationMapper->getEntityId();

                $entity = $this->entityManager
                    ->getRepository("AppBundle:$entityName")
                    ->find($entityId);

                $entitySetterName = 'set' . ucfirst($attributeName);
                $entity->$entitySetterName($content);

                $translationMapper->setAttributeContent($content);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * Get language entity groups.
     *
     * @param string $entity entity
     * @param int $entityId entity id
     * @return array
     */
    public function getTranslations(string $entity, int $entityId): array {
        $languages = $this->languageService->getAll();
        $entitiesByLanguage = [];

        foreach ($languages as $language) {
            $entitiesByLanguage[$language->getName()] = $this->translationRepository
                ->getByEntityAndEntityIdAndLanguageId(
                    $entity,
                    $entityId,
                    $language->getId(),
                    true
                );
        }

        $displayNames = $this->config['display_names'];

        foreach ($entitiesByLanguage as &$languageGroup) {
            foreach ($languageGroup as &$entityGroup) {
                $entityGroup['attributeDisplayName'] = $displayNames[$entityGroup['attribute']];
            }
        }

        return $entitiesByLanguage;
    }

    /**
     * Get count of pages.
     *
     * @param int $maxResults max results
     * @return int
     */
    public function getPaginationPagesCount(int $maxResults): int {
        return ceil($this->translationMapperRepository->getCountGroupedByEntityIdAndEntity() / $maxResults);
    }

    /**
     * Get translations limited by first result and max results.
     *
     * @param int $firstResult first result
     * @param int $maxResults max results
     * @return array
     */
    public function getPaginationPage(int $firstResult, int $maxResults): array {
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
     * Update translation mappers.
     *
     * @param string $entity entity name
     * @param array $namesOfEntityAttributes names of entity attributes
     */
    private function updateTranslationMappers(string $entity, array $namesOfEntityAttributes) {
        $idsOfEntities = $this->translationMapperRepository->getEntityIdByEntity($entity);

        if (count($idsOfEntities) === 0) {
            return;
        }

        $idsOfEntitiesVector = $this->dqlHelper->convertToVector($idsOfEntities, 'entityId');
        $idsOfEntitiesDQL = $this->dqlHelper->convertToString($idsOfEntitiesVector);
        $namesOfAttributesWithAliasDQL = $this->dqlHelper->getAttributesWithAliasDQL($namesOfEntityAttributes);

        $valuesOfEntities = $this->entityManager
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

                $this->translationMapperRepository
                    ->updateAttributeContent($content, $name, (int) $attributes['id'], $entity);
            }
        }
    }

    /**
     * Update translations.
     *
     * @param string $entity entity name
     */
    private function updateTranslations(string $entity) {
        $translationMappers = $this->translationMapperRepository->getByEntity($entity);

        foreach ($translationMappers as &$translationMapper) {
            $translations = $translationMapper->getTranslations();
            foreach ($translations as &$translation) {
                if ($translation->getLanguage()->getIsDefault() === true) {
                    $translation->setContent($translationMapper->getAttributeContent());
                }
            }
        }

        $this->entityManager->flush();
    }

    /**
     * Create new translation mappers.
     *
     * @param string $entity entity name
     * @param array $namesOfEntityAttributes names of entity attributes
     * @param array $entityAttributes entity attributes
     */
    private function createTranslationMappers(string $entity, array $namesOfEntityAttributes, array $entityAttributes) {
        $idsOfEntities = $this->translationMapperRepository->getEntityIdByEntity($entity);
        $namesOfAttributesWithAliasDQL = $this->dqlHelper->getAttributesWithAliasDQL($namesOfEntityAttributes);
        $defaultLanguage = $this->languageService->getDefaultLanguage();

        $dql = "SELECT $namesOfAttributesWithAliasDQL FROM AppBundle:$entity e";
        if (count($idsOfEntities) !== 0) {
            $idsOfEntitiesVector = $this->dqlHelper->convertToVector($idsOfEntities, 'entityId');
            $idsOfEntitiesDQL = $this->dqlHelper->convertToString($idsOfEntitiesVector);

            $dql = "SELECT $namesOfAttributesWithAliasDQL FROM AppBundle:$entity e WHERE e.id NOT IN($idsOfEntitiesDQL)";
        }

        $valuesOfEntities = $this->entityManager->createQuery($dql)->getArrayResult();

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
                    ->setLanguage($defaultLanguage);
                $this->entityManager->persist($translationMapper);
                $this->persistTranslations($translationMapper);
                $this->entityManager->flush();
            }
        }
    }

    /**
     * Persist translations.
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

            $this->entityManager->persist($translation);
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

        $idsDQL = $this->dqlHelper->convertToString($ids, ',');
        $this->translationRepository->delete($idsDQL);
    }

    /**
     * Remove entries that do not exist and remove non-existing attributes of entities.
     *
     * @param string $entityName entity name
     * @param array $namesOfEntityAttributes names of entity attributes
     */
    private function removeInvalidTranslationMappers(string $entityName, array $namesOfEntityAttributes) {
        $idsOfEntities = $this->dqlHelper->getIds($this->entityManager, $entityName);
        $idsOfEntitiesVector = $this->dqlHelper->convertToVector($idsOfEntities, 'id');
        $idsOfEntitiesDQL = $this->dqlHelper->convertToString($idsOfEntitiesVector, ',');
        $namesOfAttributesWrappedDQL = $this->dqlHelper->convertToString($namesOfEntityAttributes, ',', "'");
        $this->translationMapperRepository->delete($idsOfEntitiesDQL, $namesOfAttributesWrappedDQL, $entityName);
    }
}
