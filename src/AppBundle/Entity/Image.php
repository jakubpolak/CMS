<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @author Jakub Polák, Jana Poláková
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 */
class Image implements Entity {
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var File
     */
    private $file;

    /**
     * @var string
     */
    private $wwwRoot;

    /**
     * @var string
     */
    private $uploadDir;

    /**
     * @var Article
     *
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="images")
     * @ORM\JoinColumn(referencedColumnName="id", name="article_id")
     */
    private $article;

    /**
     * @var ImageType
     *
     * @ORM\ManyToOne(targetEntity="ImageType", inversedBy="images")
     * @ORM\JoinColumn(referencedColumnName="id", name="image_type_id")
     */
    private $imageType;

    /**
     * Constructor.
     */
    public function __construct($wwwRoot, $uploadDir) {
        $this->wwwRoot = $wwwRoot;
        $this->uploadDir = $uploadDir;
        $this->position = 0;
    }

    /**
     * Get www root.
     *
     * @return string
     */
    public function getWwwRoot() {
        return $this->wwwRoot;
    }

    /**
     * Set www root.
     *
     * @param string $wwwRoot
     * @return self
     */
    public function setWwwRoot($wwwRoot) {
        $this->wwwRoot = $wwwRoot;

        return $this;
    }


    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     * @return self
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set position.
     *
     * @param integer $position
     * @return self
     */
    public function setPosition($position) {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position.
     *
     * @return int
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * Get file.
     *
     * @return File
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * Set file.
     *
     * @param File $file
     * @return $this
     */
    public function setFile($file) {
        $this->file = $file;

        return $this;
    }

    /**
     * Remove file.
     *
     * return $this;
     */
    public function removeFile(){
        @unlink($this->getAbsolutePath());
    }

    /**
     * Get article.
     *
     * @return self
     */
    public function getArticle() {
        return $this->article;
    }

    /**
     * Set article.
     *
     * @param Article $article
     * @return self
     */
    public function setArticle($article) {
        $this->article = $article;

        return $this;
    }

    /**
     * Get imageType.
     *
     * @return ImageType
     */
    public function getImageType() {
        return $this->imageType;
    }

    /**
     * Set imageType.
     *
     * @param ImageType $imageType
     * @return self
     */
    public function setImageType($imageType) {
        $this->imageType = $imageType;

        return $this;
    }

    /**
     * Get web path.
     *
     * @return null|string
     */
    public function getWebPath() {
        return null === $this->name
            ? null
            : $this->getUploadDir().'/'.$this->name;
    }

    /**
     * Get absolute path.
     *
     * @return null|string
     */
    public function getAbsolutePath() {
        return null === $this->name
            ? null
            : $this->getUploadRootDir() . '/' . $this->name;
    }

    /**
     * Get upload root dir.
     *
     * @return string
     */
    public function getUploadRootDir() {
        return __DIR__ . '/../../../' . $this->wwwRoot . '/' . $this->uploadDir;
    }

    /**
     * Set upload directory.
     *
     * @param string $uploadDir
     * @return self
     */
    public function setUploadDir($uploadDir) {
        $this->uploadDir = $uploadDir;

        return $this;
    }

    /**
     * Get upload directory.
     *
     * @return string
     */
    public function getUploadDir() {
        return $this->uploadDir;
    }

    /**
     * Upload file.
     */
    public function upload() {
        $name = uniqid('img_', false) . '.' . $this->file->guessExtension();
        $this->file->move($this->getUploadRootDir(), $name);
        $this->setName($name);
        $this->file = null;
    }

    /**
     * Delete file.
     *
     * return self;
     */
    public function delete() {
        @unlink($this->getAbsolutePath());
    }
}

