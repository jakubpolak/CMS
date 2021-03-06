<?php

namespace AppBundle\Service;
use AppBundle\Entity\Image;
use AppBundle\Entity\ImageType;
use AppBundle\Repository\ImageRepository;
use AppBundle\Repository\ImageTypeRepository;
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
     * @var ImageTypeRepository
     */
    private $imageTypeRepository;

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
        $this->imageTypeRepository = $this->em->getRepository('AppBundle:ImageType');
        $this->wwwRoot = $wwwRoot;
        $this->uploadDir = $uploadDir;
    }

    /**
     * Get instance of image.
     *
     * @return Image
     */
    public function getInstance() : Image {
        return new Image($this->wwwRoot, $this->uploadDir);
    }

    /**
     * Get all images order by position.
     * 
     * @return array
     */
    public function getAllToSliderOrderByPosition() : array {
        $imageType = $this->imageTypeRepository->getByName(ImageType::SLIDER);

        if ($imageType !== null) {
            return $this->imageRepository->getAllToSliderOrderByPosition($imageType);
        } else {
            return false;
        }
    }

    /**
     * Save image.
     *
     * @param Image $image image
     * @param string $imageTypeName name of image type
     * @throws ServiceException
     */
    public function save(Image $image, string $imageTypeName) {
        $this->setPaths($image);

        if ($image->getFile() !== null) {
            $image->removeFile();
            $image->upload();
        }

        $imageType = $this->imageTypeRepository->getByName($imageTypeName);
        $image->setImageType($imageType);

        if ($image->getId() === null) {
            $this->em->persist($image);
        }
        
        try {
            $this->em->flush();
        } catch (\Exception $e) {
            if ($image->getFile() !== null) {
                $image->removeFile();
            }

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
