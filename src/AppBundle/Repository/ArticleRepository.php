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
     * Get all articles by is published order by date of written.
     *
     * @param bool|bool $isPublished
     * @return array
     */
    public function getAllByIsPublished(bool $isPublished) {
        $qb = $this->_em->getRepository('AppBundle:Article')->createQueryBuilder('a');

        $qb->orderBy('a.writtenOn', 'ASC');

        if ($isPublished === true){
            $qb->andWhere('a.isPublished = 1');
        }

        return $qb->getQuery()->getResult();
    }
}
