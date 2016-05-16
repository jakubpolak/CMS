<?php

namespace AppBundle\Twig;

use AppBundle\Repository\MenuRepository;
use Doctrine\ORM\EntityManager;

/**
 * Menu helper extension.
 *
 * @author Jakub Polák, Jana Poláková
 */
class MenuHelperExtension extends \Twig_Extension {
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var MenuRepository
     */
    private $menuRepository;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager entity manager
     */
    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
        $this->menuRepository = $this->em->getRepository('AppBundle:Menu');
    }

    /**
     * Get functions.
     *
     * @return array
     */
    public function getFunctions(): array {
        return [
            new \Twig_SimpleFunction('getMenuTree', [$this, 'getMenuTreeFunction'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Get menu tree function.
     *
     * @param $isActive
     * @return array
     */
    public function getMenuTreeFunction(bool $isActive): array {
        return $this->menuRepository->getMenu($isActive);
    }

    /**
     * Get name.
     *
     * @return string|string
     */
    public function getName(): string {
        return 'menuHelper_extension';
    }
}