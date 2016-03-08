<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

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
     * @var EncoderFactory
     */
    private $encoderFactory;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager entity manager
     * @param EncoderFactory $encoderFactory encoder factory
     */
    public function __construct(EntityManager $entityManager, EncoderFactory $encoderFactory) {
        $this->em = $entityManager;
        $this->userRepository = $this->em->getRepository('AppBundle:User');
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * Save user.
     *
     * @param User $user
     */
    public function save(User $user) {
        $userId = $user->getId();
        $userPassword = $user->getPassword();

        if ($userId === null) { $this->em->persist($user); }

        if ($userPassword === null) { // Updating existing user.
            $data = $this->userRepository->getById($userId);
            $encodePassword = $data['password'];
            $encodeSalt = $data['salt'];
        } else { // Creating a new user or updating an existing user.
            $encodePassword = $user->getPassword();
            $encodeSalt = $user->getSalt();
        }

        $password = $this->encoderFactory
            ->getEncoder($user)
            ->encodePassword($encodePassword, $encodeSalt)
        ;

        $user->setPassword($password);
        $this->em->flush();
    }

    /**
     * Get all users.
     *
     * @return array
     */
    public function getAll(): array {
        return $this->userRepository->findAll();
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
