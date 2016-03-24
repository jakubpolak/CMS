<?php

namespace AppBundle\Service;

use AppBundle\Entity\StaticPage;
use AppBundle\Repository\StaticPageRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * StaticPage service.
 *
 * @author Jakub Polák, Jana Poláková
 */
class StaticPageService extends CrudService {
    /**
     * @var StaticPageRepository
     */
    private $staticPageRepository;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager entity manager
     */
    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager);
        $this->staticPageRepository = $this->em->getRepository('AppBundle:StaticPage');
    }

    /**
     * Get repository.
     *
     * @return EntityRepository
     */
    public function getRepository(): EntityRepository {
        return $this->staticPageRepository;
    }
}