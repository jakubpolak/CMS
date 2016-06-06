<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Image repository.
 *
 * @author Jakub Polák, Jana Poláková
 */
class ImageRepository extends EntityRepository {
    /**
     * Get all images to slider order by position.
     * 
     * @param $imageType
     * @return array
     */
    public function getAllToSliderOrderByPosition($imageType): array {
        $qb = $this->_em->getRepository('AppBundle:Image')->createQueryBuilder('i')->where('i.imageType = :imageType');
        $qb->orderBy('i.position', 'ASC')
            ->setParameter('imageType', $imageType);
        
        return $qb->getQuery()->getResult();
    }

    /**
     * Get first image of images.
     *
     * @param $article
     * @return array
     */
    public function getFirstImage($article) {
        return $this->_em
            ->createQuery('SELECT i
              FROM AppBundle:Image i
              WHERE i.article = :article
              ORDER BY i.position ASC
              ')
            ->setParameter('article', $article)
            ->setMaxResults(1)
            ->useQueryCache(true)
            ->getResult();
    }
}
