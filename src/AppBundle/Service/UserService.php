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
class UserService {
    /**
     * @var EntityManager
     */
    protected $em;

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
        $this->em = $entityManager;
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

    /**
     * Save user.
     *
     * @param User $user
     */
    public function save(User $user) {
        if ($user->getId() === null){
            $this->em->persist($user);
        }
        $this->em->flush();
    }

    /**
     * Get all users.
     *
     * @return array
     */
    public function getAll(): array {
        return $this->getRepository()->findAll();
    }

    /**
     * Delete user.
     *
     * @param User $user
     */
    public function delete(User $user) {
        $this->em->remove($user);
        $this->em->flush();
    }
}
