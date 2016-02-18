<?php

namespace AppBundle\Service;

use AppBundle\Entity\Entity;
use AppBundle\Entity\Language;
use AppBundle\Entity\Slug;
use AppBundle\Helper\ReflectionHelper;
use AppBundle\Repository\SlugRepository;
use AppBundle\Service\Exception\ServiceException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

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
     * SlugService constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
        $this->slugRepository = $this->em->getRepository('AppBundle:Slug');
    }

    /**
     * Get entity
     *
     * @param string $entityName
     * @param int $entityId
     * @return null|Entity
     */
    public function getEntity(string $entityName, int $entityId): Entity {
        return $this->em->getRepository('AppBundle:' . ucfirst($entityName))->find($entityId);
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
    public function hasSlugs(Entity $entity): bool {
        return method_exists($entity, 'getSlugs');
    }

    /**
     * Decide whether slug exists or not for combination of specified entity and language.
     *
     * @param Entity $entity
     * @param Language $language
     * @return bool
     */
    private function slugExists(Entity $entity, Language $language): bool {
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
    private function slugExistsExceptSpecifiedSlug(Entity $entity, Language $language, Slug $slug): bool {
        $entityName = ReflectionHelper::getClassName($entity);
        $entityName = lcfirst($entityName);
        $count = $this->slugRepository->getCountByEntityAndLanguageAndNotSlug($entity, $entityName, $language, $slug);

        return $count > 0;
    }
}
