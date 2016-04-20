<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Language repository.
 *
 * @author Jakub Polák, Jana Poláková
 */
class LanguageRepository extends EntityRepository {
    /**
     * Get count of languages.
     *
     * @return int
     */
    public function getCount(): int {
        return (int) $this->_em->createQuery('SELECT COUNT(l.id) FROM AppBundle:Language l')
            ->useQueryCache(true)
            ->getSingleScalarResult();
    }

    /**
     * Set isDefault to all languages.
     *
     * @param bool $isDefault is default
     */
    public function setDefault(bool $isDefault) {
        $this->_em->createQuery('UPDATE AppBundle:Language l SET l.isDefault = :isDefault')
            ->useQueryCache(true)
            ->setParameter('isDefault', $isDefault)
            ->execute();
    }

    /**
     * Get all languages. First language is default.
     * 
     * @return array
     */
    public function getAll(): array {
        return $this->_em->createQuery('SELECT l FROM AppBundle:Language l ORDER BY l.isDefault DESC')
            ->useQueryCache(true)
            ->getResult();
    }
}
