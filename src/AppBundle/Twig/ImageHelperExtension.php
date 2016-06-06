<?php

namespace AppBundle\Twig;

use AppBundle\Repository\ImageRepository;
use Doctrine\ORM\EntityManager;

/**
 * Image helper extension.
 *
 * @author Jakub Polák, Jana Poláková
 */
class ImageHelperExtension extends \Twig_Extension {
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ImageRepository
     */
    private $imageRepository;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager entity manager
     */
    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
        $this->imageRepository = $this->em->getRepository('AppBundle:Image');
    }

    /**
     * Get functions.
     *
     * @return array
     */
    public function getFunctions(): array {
        return [
            new \Twig_SimpleFunction('getFirstImage', [$this, 'getFirstImageFunction'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Get first image function.
     *
     * @param $article
     * @return array
     */
    public function getFirstImageFunction($article) {
        $image = $this->imageRepository->getFirstImage($article);
        return ($image) ? $image : null;
    }

    /**
     * Get name.
     *
     * @return string|string
     */
    public function getName(): string {
        return 'imageHelper_extension';
    }
}