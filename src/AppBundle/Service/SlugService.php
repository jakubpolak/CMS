<?php

namespace AppBundle\Service;

use AppBundle\Entity\Article;
use AppBundle\Entity\Entity;
use AppBundle\Entity\Language;
use AppBundle\Entity\Menu;
use AppBundle\Entity\Slug;
use AppBundle\Helper\ReflectionHelper;
use AppBundle\Repository\SlugRepository;
use AppBundle\Service\Exception\ServiceException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;

/**
 * Slug service.
 *
 * @author Jakub Polák, Jana Poláková
 */
class SlugService {
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var SlugRepository
     */
    private $slugRepository;

    /**
     * @var LanguageService
     */
    private $languageService;
    
    /**
     * @var array
     */
    private $slugTypes = [Slug::MENU, Slug::ARTICLE];

    /**
     * SlugService constructor.
     *
     * @param EntityManager $entityManager
     * @param LanguageService $languageService
     */
    public function __construct(EntityManager $entityManager, LanguageService $languageService) {
        $this->em = $entityManager;
        $this->slugRepository = $this->em->getRepository('AppBundle:Slug');
        $this->languageService = $languageService;
    }

    /**
     * Get entity
     *
     * @param string $entityName
     * @param int $entityId
     * @return null|Entity
     */
    public function getEntity(string $entityName, int $entityId) : Entity {
        return $this->em->getRepository('AppBundle:' . ucfirst($entityName))->find($entityId);
    }

    /**
     * Get entity by slug content or entity id.
     *
     * @param string $slugType slug type
     * @param string $slug slug content or a combination of entity class and entity id
     * @return Entity
     */
    public function getEntityBySlugTypeAndSlugOrId(string $slugType, string $slug) : Entity {
        $classNameAndId = $this->getClassNameAndId($slug);
        $entity = null;
        
        switch ($slugType) {
            case Slug::MENU:
                $entity = ($classNameAndId === false) 
                    ? $this->slugRepository->getByContent($slug)->getMenu()
                    : $this->em->getRepository('AppBundle:Menu')->find($classNameAndId['id']);
                break;
            case Slug::ARTICLE:
                $entity = ($classNameAndId === false)
                    ? $this->slugRepository->getByContent($slug)->getArticle()
                    : $this->em->getRepository('AppBundle:Article')->find($classNameAndId['id']);
                break;
        }

        
        return $entity;
    }
    
    /**
     * Get slug by entity and locale.
     * 
     * @param Entity $entity entity
     * @param string $locale locale
     * @return Slug|int 
     * @throws NoResultException thrown in case that slug does not exist
     */
    public function getByEntityAndLocale(Entity $entity, string $locale) {
        $language = $this->languageService->getByCode($locale);
        $class = ReflectionHelper::getClassName($entity);

        $slug = null;
        
        switch ($class) {
            case Slug::MENU:
                $slug = $this->slugRepository->getByArticleAndLanguage($entity, $language);
                break;
            case Slug::ARTICLE:
                $slug = $this->slugRepository->getByMenuAndLanguage($entity, $language);
                break;
        }

        return $slug;
    }

    /**
     * Save slug.
     *
     * @param Slug $slug slug
     * @param Entity $entity entity
     * @throws ServiceException service exception
     */
    public function save(Slug $slug, Entity $entity) {
        $entityName = ReflectionHelper::getClassName($entity);
        $setMethod = 'set' . $entityName;
        $slug->$setMethod($entity);
        $language = $slug->getLanguage();

        $slugId = $slug->getId();

        if (($slugId === null && $this->slugExists($entity, $language)) ||
            ($slugId !== null && $this->slugExistsExceptSpecifiedSlug($entity, $language, $slug))) {
            $message = 'Slug for entity "'
                . $entityName
                . '" with content "'
                . $slug->getContent()
                . '" in language "'
                . $language->getCode()
                .  '" already exists.'
            ;

            throw new ServiceException($message);
        }

        if ($slugId === null) {
            $this->em->persist($slug);
        }

        $this->em->flush();
    }

    /**
     * Delete slug.
     *
     * @param Slug $slug
     */
    public function delete(Slug $slug) {
        $this->em->remove($slug);
        $this->em->flush();
    }

    /**
     * Decide whether entity has slugs or not.
     *
     * @param Entity $entity entity
     * @return bool
     */
    public function hasSlugs(Entity $entity) : bool {
        return method_exists($entity, 'getSlugs');
    }

    /**
     * Decide whether slug exists or not for combination of specified entity and language.
     *
     * @param Entity $entity
     * @param Language $language
     * @return bool
     */
    private function slugExists(Entity $entity, Language $language) : bool {
        $entityName = ReflectionHelper::getClassName($entity);
        $entityName = lcfirst($entityName);
        $count = $this->slugRepository->getCountByEntityAndLanguage($entity, $entityName, $language);

        return $count > 0;
    }


    /**
     * Decide whether slug exists or not for combination of specified entity and language and not slug.
     *
     * @param Entity $entity
     * @param Language $language
     * @param Slug $slug
     * @return bool
     */
    private function slugExistsExceptSpecifiedSlug(Entity $entity, Language $language, Slug $slug) : bool {
        $entityName = ReflectionHelper::getClassName($entity);
        $entityName = lcfirst($entityName);
        $count = $this->slugRepository->getCountByEntityAndLanguageAndNotSlug($entity, $entityName, $language, $slug);

        return $count > 0;
    }

    /**
     * Get class name and id from slug.
     *
     * @param string $slug
     * @return array|false ['class_name' => ... , 'id' => ....] on success, false otherwise.
     */
    private function getClassNameAndId(string $slug) {
        $classNameAndId = explode('-', $slug);

        if (count($classNameAndId) !== 2 || !in_array($classNameAndId[0], $this->slugTypes)) {
            return false;
        }

        return [
            'class_name' => $classNameAndId[0],
            'id' => $classNameAndId[1]
        ];
    }
}
