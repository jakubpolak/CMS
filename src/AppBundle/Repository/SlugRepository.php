<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Entity;
use AppBundle\Entity\Language;
use AppBundle\Entity\Slug;
use Doctrine\ORM\EntityRepository;

/**
 * Slug repository.
 *
 * @author Jakub Polák, Jana Poláková
 */
class SlugRepository extends EntityRepository {
    /**
     * Get count of entities.
     *
     * @param Entity $entity
     * @param string $entityName
     * @param Language $language
     * @return int
     */
    public function getCountByEntityAndLanguage(Entity $entity, string $entityName, Language $language): int {
        $dql = "
            SELECT COUNT(s.id)
            FROM AppBundle:Slug s
            WHERE s.{$entityName} = :entity
            AND s.language = :language
        ";

        return (int) $this->_em
            ->createQuery($dql)
            ->setParameters([
                'entity' => $entity,
                'language' => $language
            ])->useQueryCache(true)
            ->getSingleScalarResult()
        ;
    }

    /**
     * Get count of entities.
     *
     * @param Entity $entity
     * @param string $entityName
     * @param Language $language
     * @param Slug $slug
     * @return int
     */
    public function getCountByEntityAndLanguageAndNotSlug(Entity $entity, string $entityName, Language $language, Slug $slug): int {
        $dql = "
            SELECT COUNT(s.id)
            FROM AppBundle:Slug s
            WHERE s.{$entityName} = :entity
            AND s.language = :language
            AND s != :slug
        ";

        return (int) $this->_em
            ->createQuery($dql)
            ->setParameters([
                'entity' => $entity,
                'language' => $language,
                'slug' => $slug
            ])->useQueryCache(true)
            ->getSingleScalarResult()
        ;
    }
}
