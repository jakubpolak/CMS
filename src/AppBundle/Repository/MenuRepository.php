<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Menu;
use Doctrine\ORM\EntityRepository;

/**
 * Menu repository.
 *
 * @author Jakub Polák, Jana Poláková
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
     * Get menu.
     *
     * @param bool|null $isActive is active.
     * @return array
     */
    public function getMenu($isActive = null){
        return $this->getTree($this->getParents($isActive), [], $isActive);
    }

    /**
     * Get parents.
     *
     * @param bool|null $isActive is active
     * @return array
     */
    public function getParents($isActive = null){
        $qb = $this->_em->getRepository('AppBundle:Menu')->createQueryBuilder('m');

        $qb->where('m.menu IS NULL')->orderBy('m.position', 'ASC');

        if ($isActive === true){
            $qb->andWhere('m.isActive = 1');
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Get children.
     *
     * @param Menu $parentMenu parent menu
     * @param bool|null $isActive is active
     * @return array
     */
    public function getChildren(Menu $parentMenu, $isActive = null){
        $qb = $this->_em->getRepository('AppBundle:Menu')->createQueryBuilder('m');

        $qb->where('m.menu = :parentMenu')
            ->orderBy('m.position', 'ASC')
            ->setParameter('parentMenu', $parentMenu)
        ;

        if ($isActive === true){
            $qb->andWhere('m.isActive = 1');
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Get tree.
     *
     * @param array $parents parents
     * @param array $tree tree
     * @param bool $isActive is active
     * @return array
     */
    public function getTree(array $parents, array $tree = [], $isActive = null){
        foreach ($parents as $parent){
            $tree[$parent->getId()] = $parent;
            $children = $this->getChildren($parent, $isActive);

            if (!empty($children)){
                $tree[$parent->getId()]->setMenus($children);
                $this->getTree($children, $tree, $isActive);
            }
        }
        return $tree;
    }
}
