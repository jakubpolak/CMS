<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @author Jakub Polák, Jana Poláková
 *
 * @ORM\Table(
 *     name="translation",
 *     uniqueConstraints={@UniqueConstraint(name="translation_language_unique", columns={"translation_id", "language_id"})}
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TranslationRepository")
 */
class Translation {
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
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="translations")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    private $language;

    /**
     * @var TranslationMapper
     *
     * @ORM\ManyToOne(targetEntity="TranslationMapper", inversedBy="translations")
     * @ORM\JoinColumn(name="translation_id")
     */
    private $translationMapper;

    /**
     * Get id.
     *
     * @return int|null
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param int $id
     * @return Translation
     */
    public function setId(int $id): self {
        $this->id = $id;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent(): string {
        return $this->content;
    }

    /**
     * Set content.
     *
     * @param string $content
     * @return Translation
     */
    public function setContent(string $content): self {
        $this->content = $content;

        return $this;
    }

    /**
     * Get language.
     *
     * @return Language
     */
    public function getLanguage(): Language {
        return $this->language;
    }

    /**
     * Set language.
     *
     * @param Language $language
     * @return Translation
     */
    public function setLanguage(Language $language): self {
        $this->language = $language;

        return $this;
    }

    /**
     * Get translation mapper.
     *
     * @return TranslationMapper
     */
    public function getTranslationMapper(): TranslationMapper {
        return $this->translationMapper;
    }

    /**
     * Set translation mapper.
     *
     * @param TranslationMapper $translationMapper
     * @return Translation
     */
    public function setTranslationMapper(TranslationMapper $translationMapper): self {
        $this->translationMapper = $translationMapper;

        return $this;
    }
}