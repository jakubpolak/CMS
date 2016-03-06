<?php

namespace AppBundle\Service;
use AppBundle\Entity\Image;
use AppBundle\Repository\ImageRepository;
use AppBundle\Service\Exception\ServiceException;
use Doctrine\ORM\EntityManager;

/**
 * Image service.
 *
 * @author Jakub Polák, Jana Poláková
 */
class ImageService {
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ImageRepository
     */
    private $imageRepository;

    /**
     * @var string
     */
    private $wwwRoot;

    /**
     * @var string
     */
    private $uploadDir;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager entity manager
     * @param string $wwwRoot www root
     * @param string $uploadDir upload directory
     */
    public function __construct(EntityManager $entityManager, string $wwwRoot, string $uploadDir) {
        $this->em = $entityManager;
        $this->imageRepository = $this->em->getRepository('AppBundle:Image');
        $this->wwwRoot = $wwwRoot;
        $this->uploadDir = $uploadDir;
    }

    /**
     * Get instance of image.
     *
     * @return Image
     */
    public function getInstance() {
        return new Image($this->wwwRoot, $this->uploadDir);
    }

    /**
     * Save image.
     *
     * @param Image $image
     * @throws ServiceException
     */
    public function save(Image $image) {
        $this->setPaths($image);

        if ($image->getFile() !== null) {
            $image->removeFile();
            $image->upload();
        }

        if ($image->getId() === null) { $this->em->persist($image); }

        try {
            $this->em->flush();
        } catch (\Exception $e) {
            if ($image->getFile() !== null) { $image->removeFile(); }

            throw new ServiceException($e);
        }
    }

    /**
     * Delete image.
     *
     * @param Image $image
     */
    public function delete(Image $image) {
        $this->setPaths($image);

        $file = $image->getAbsolutePath();

        $this->em->remove($image);
        $this->em->flush();

        @unlink($file);
    }

    /**
     * Set paths.
     *
     * @param Image $image
     */
    private function setPaths(Image $image) {
        $image->setUploadDir($this->uploadDir);
        $image->setWwwRoot($this->wwwRoot);
    }
}
