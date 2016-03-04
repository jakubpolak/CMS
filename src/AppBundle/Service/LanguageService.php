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
     * Constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
        $this->languageRepository = $this->em->getRepository('AppBundle:Language');
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
     * Get all entities.
     *
     * @return array
     */
    public function getAll(): array {
        return $this->getRepository()->findAll();
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
