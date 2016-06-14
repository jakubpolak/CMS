<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Language;
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
        return (int) $this->getEntityManager()->createQuery('SELECT COUNT(l.id) FROM AppBundle:Language l')
            ->useQueryCache(true)
            ->getSingleScalarResult();
    }

    /**
     * Set isDefault to all languages.
     *
     * @param bool $isDefault is default
     */
    public function setDefault(bool $isDefault) {
        $this->getEntityManager()->createQuery('UPDATE AppBundle:Language l SET l.isDefault = :isDefault')
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
        return $this->getEntityManager()->createQuery('SELECT l FROM AppBundle:Language l ORDER BY l.isDefault DESC')
            ->useQueryCache(true)
            ->getResult();
    }

    /**
     * Get single result.
     *
     * @param string $code
     * @return Language
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByCode(string $code) {
        return $this->getEntityManager()->createQuery('SELECT l FROM AppBundle:Language l WHERE l.code = :code')
            ->setParameter('code', $code)
            ->getSingleResult();
    }
}
