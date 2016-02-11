<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * User service.
 *
 * @author Jakub Polák, Jana Poláková
 */
class UserService extends CrudService {
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager entity manager
     */
    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager);
        $this->userRepository = $this->em->getRepository('AppBundle:User');
    }

    /**
     * Get repository.
     *
     * @return EntityRepository
     */
    public function getRepository(): EntityRepository {
        return $this->userRepository;
    }
}
