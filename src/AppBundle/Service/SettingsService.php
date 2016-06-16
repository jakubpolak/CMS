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
class SettingsService extends CrudService{
    /**
     * @var Settings
     */
    private static $settings;

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
        if (self::$settings === null) {
            self::$settings = new Settings();
            $this->em->persist(self::$settings);
            $this->em->flush();
        } else {
            self::$settings = $this->settingsRepository->find(self::$settings->getId());
        }

        return self::$settings;
    }
}
