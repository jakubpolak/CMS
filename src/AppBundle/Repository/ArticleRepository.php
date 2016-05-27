<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Article repository.
 *
 * @author Jakub Polák
 */
class ArticleRepository extends EntityRepository {
    /**
     * Get articles by is published.
     * 
     * @param bool $isPublished is published     
     * @return array
     */
    public function getByPublished(bool $isPublished) : array {
        return $this->_em
            ->createQuery('SELECT a FROM AppBundle:Article a WHERE a.isPublished = :published')
            ->setParameter('published', $isPublished)
            ->useQueryCache(true)
            ->getResult();
    }
}
