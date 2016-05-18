<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Slug repository.
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
}
