<?php

namespace AppBundle\Service;

use AppBundle\Entity\Settings;
use AppBundle\Repository\SettingsRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Settings service.
 *
 * @author Jakub Polák, Jana Poláková
 */
class SettingsService extends CrudService {
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;

    /**
     * Constructor.
     * 
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager);
        $this->settingsRepository = $this->em->getRepository('AppBundle:Settings');
    }

    /**
     * Get repository.
     *
     * @return EntityRepository
     */
    public function getRepository() : EntityRepository {
        return $this->settingsRepository;
    }

    /**
     * Get settings
     *
     * @return Settings
     */
    public function getSettings() {
        $settings = $this->settingsRepository->find(Settings::ID);

        if ($settings === null) {
            $settings = new Settings();
            $this->em->persist($settings);
            $this->em->flush();
        }

        return $settings;
    }
}
