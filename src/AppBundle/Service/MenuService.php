<?php

namespace AppBundle\Service;

use AppBundle\Entity\Menu;
use Doctrine\ORM\EntityManager;

/**
 * Menu service.
 *
 * @package AppBundle\Service
 */
class MenuService {
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    /**
     * Save a menu.
     *
     * @param Menu $menu
     */
    public function save(Menu $menu) {
        if ($menu->getId() === null) {
            $this->em->persist($menu);
        }
        $this->em->flush();
    }
}
