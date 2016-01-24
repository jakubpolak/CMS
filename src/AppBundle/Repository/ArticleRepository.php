<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @author Jakub Polák
 */
class ArticleRepository extends EntityRepository {
    /**
     * Get articles by firstResult and maxResults.
     *
     * @param int $firstResult
     * @param int $maxResults
     * @return array
     */
    public function getByFirstResultAndMaxResults(int $firstResult, int $maxResults): array {
        return $this->_em
            ->createQuery('SELECT a FROM AppBundle:Article a')
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResults)
            ->useQueryCache(true)
            ->getResult()
        ;
    }
}
