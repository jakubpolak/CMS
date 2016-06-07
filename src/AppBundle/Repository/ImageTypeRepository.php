<?php

namespace AppBundle\Repository;

use AppBundle\Entity\ImageType;
use Doctrine\ORM\EntityRepository;

/**
 * Image Type Repository.
 *
 * @author Jakub Polák, Jana Poláková
 */
class ImageTypeRepository extends EntityRepository {
    /**
     * Get by name.
     * 
     * @param string $name image type name
     * @return ImageType
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByName(string $name) : ImageType {
        return $this->getEntityManager()
            ->createQuery('SELECT it FROM AppBundle:ImageType it WHERE it.name = :name')
            ->setParameter('name', $name)
            ->useQueryCache(true)
            ->useResultCache(true)
            ->getSingleResult();
    }
}
