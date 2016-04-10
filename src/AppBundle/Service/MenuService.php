<?php

namespace AppBundle\Service;

use AppBundle\Entity\Menu;
use AppBundle\Repository\MenuRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Menu service.
 *
 * @author Jakub Polák, Jana Poláková
 */
class MenuService extends CrudService {
    /**
     * @var MenuRepository
     */
    private $menuRepository;

    /**
     * Constructor
     *
     * @param EntityManager $entityManager entity manager
     */
    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager);
        $this->menuRepository = $this->em->getRepository('AppBundle:Menu');
    }

    /**
     * Get repository.
     *
     * @return EntityRepository
     */
    public function getRepository(): EntityRepository {
        return $this->menuRepository;
    }

    /**
     * Get parents.
     *
     * @param bool|null $isActive is active
     * @return array
     */
    public function getParents(bool $isActive = null) {
        return ($isActive === null)
            ? $this->menuRepository->getTopLevel()
            : $this->menuRepository->getTopLevelByIsActive($isActive)
        ;
    }

    /**
     * Get menu.
     *
     * @param bool|null $isActive is active
     * @return array
     */
    public function getMenu(bool $isActive = null) {
        return $this->menuRepository->getMenu($isActive);
    }
}
