<?php

namespace AppBundle\Service;

use AppBundle\Entity\Language;
use AppBundle\Repository\LanguageRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Language service.
 *
 * @author Jakub Polák, Jana Poláková
 */
class LanguageService {
    /**
     * @var LanguageRepository
     */
    private $languageRepository;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var string
     */
    private $locale;

    /**
     * Constructor.
     * 
     * @param EntityManager $entityManager
     * @param string $locale
     */
    public function __construct(EntityManager $entityManager, string $locale) {
        $this->em = $entityManager;
        $this->languageRepository = $this->em->getRepository('AppBundle:Language');
        $this->locale = $locale;
    }

    /**
     * Get repository.
     *
     * @return EntityRepository
     */
    public function getRepository(): EntityRepository {
        return $this->languageRepository;
    }

    /**
     * Get language by it's code.
     * 
     * @param string $code language code
     * @return Language
     */
    public function getByCode(string $code) : Language {
        return $this->languageRepository->getByCode($code);
    }

    /**
     * Get default language.
     * 
     * @return Language
     */
    public function getDefaultLanguage() : Language{
        return $this->languageRepository->getByCode($this->locale);
    }

    /**
     * Get all languages. First language is default.
     *
     * @return array
     */
    public function getAll(): array {
        return $this->languageRepository->getAll();
    }

    /**
     * Save language.
     *
     * @param Language $language
     * @throws \Exception
     */
    public function save(Language $language) {
        if ($language->getIsDefault() === true) {
            $this->languageRepository->setDefault(false);
        }

        if ($language->getId() === null) {
            $this->em->persist($language);
        }

        $this->em->flush();
    }
}
