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
        return (int) $this->_em->createQuery("SELECT COUNT(l.id) FROM AppBundle:Language l")
            ->useQueryCache(true)
            ->getSingleScalarResult();
    }
}
