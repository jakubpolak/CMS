<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Article;
use AppBundle\Entity\Entity;
use AppBundle\Entity\Language;
use AppBundle\Entity\Menu;
use AppBundle\Entity\Slug;
use Doctrine\ORM\EntityRepository;

/**
 * Slug repository.
 *
 * @author Jakub Polák, Jana Poláková
 */
class SlugRepository extends EntityRepository {
    /**
     * Get slug content by menu and language.
     *
     * @param Menu $menu menu
     * @param Language $language language
     * @return Slug
     */
    public function getByMenuAndLanguage(Menu $menu, Language $language) : Slug {
        return $this->getEntityManager()
            ->createQuery('SELECT s FROM AppBundle:Slug s WHERE s.menu = :menu AND s.language = :language')
            ->useQueryCache(true)
            ->setParameters(['menu' => $menu, 'language' => $language])
            ->getSingleResult();
    }

    /**
     * Get content by article and language.
     *
     * @param Article $article article
     * @param Language $language language
     * @return Slug
     */
    public function getByArticleAndLanguage(Article $article, Language $language) : Slug {
        return $this->getEntityManager()
            ->createQuery('SELECT s FROM AppBundle:Slug s WHERE s.article = :article AND s.language = :language')
            ->useQueryCache(true)
            ->setParameters(['article' => $article, 'language' => $language])
            ->getSingleResult();
    }

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

        return (int) $this->getEntityManager()
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

        return (int) $this->getEntityManager()
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
