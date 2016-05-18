<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\UnexpectedResultException;

/**
 * Image Type Repository.
 *
 * @author Jakub Polák, Jana Poláková
 */
class ImageTypeRepository extends EntityRepository {
    /**
     * Get by name.
     * 
     * @param $name
     * @return bool|mixed
     */
    public function getByName($name){
        $query = $this->_em->createQuery('SELECT it FROM AppBundle:ImageType it WHERE it.name = :name');
        $query->setParameter('name', $name)
            ->useQueryCache(true)
            ->useResultCache(true);
        try {
            return $query->getSingleResult();
        } catch (UnexpectedResultException $e) {
            return false;
        }
    }
}
