<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Menu repository.
 *
 * @author Jakub Polák
 */
class MenuRepository extends EntityRepository {
    /**
     * Get top level menu.
     *
     * @return array
     */
    public function getTopLevel(): array {
        $dql = '
            SELECT m
            FROM AppBundle:Menu m
            WHERE m.menu IS NULL
            ORDER BY m.position ASC
        ';

        return $this->_em
            ->createQuery($dql)
            ->useQueryCache(true)
            ->useResultCache(true)
            ->getResult()
        ;
    }

    /**
     * Get top level menu.
     *
     * @param bool $isActive is active
     * @return array
     */
    public function getTopLevelByIsActive(bool $isActive): array {
        $dql = '
            SELECT m
            FROM AppBundle:Menu m
            WHERE m.menu IS NULL AND m.isActive = :isActive
            ORDER BY m.position ASC
        ';

        return $this->_em
            ->createQuery($dql)
            ->setParameter('isActive', $isActive)
            ->useQueryCache(true)
            ->useResultCache(true)
            ->getResult()
        ;
    }

    /**
     * Get child menus.
     *
     * @return array
     */
    public function getChilds(): array {
        $dql = '
            SELECT m
            FROM AppBundle:Menu m
            WHERE m.menu IS NOT NULL
            ORDER BY m.position ASC
        ';

        return $this->_em
            ->createQuery($dql)
            ->useQueryCache(true)
            ->useResultCache(true)
            ->getResult()
        ;
    }

    /**
     * Get child menus by is active.
     *
     * @param bool $isActive
     * @return array
     */
    public function getChildsByIsActive(bool $isActive): array {
        $dql = '
            SELECT m
            FROM AppBundle:Menu m
            WHERE m.menu IS NOT NULL AND m.isActive = :isActive
            ORDER BY m.position ASC
        ';

        return $this->_em
            ->createQuery($dql)
            ->setParameter('isActive', $isActive)
            ->useQueryCache(true)
            ->useResultCache(true)
            ->getResult()
        ;
    }

    /**
     * Get all menus by is active.
     *
     * @param bool $isActive
     * @return array
     */
    public function getAllByIsActive(bool $isActive): array {
        $dql = '
            SELECT m
            FROM AppBundle:Menu m
            WHERE m.isActive = :isActive
            ORDER BY m.position ASC
        ';

        return $this->_em
            ->createQuery($dql)
            ->setParameter('isActive', $isActive)
            ->useQueryCache(true)
            ->useResultCache(true)
            ->getResult()
        ;
    }
}
