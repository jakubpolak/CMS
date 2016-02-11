<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * User repository.
 *
 * @author Jakub Polák, Jana Poláková
 */
class UserRepository extends EntityRepository implements UserProviderInterface {
    /**
     * Load user by username.
     *
     * @param string $username
     * @return UserInterface|void
     * @throws NoResultException
     * @throws UsernameNotFoundException
     */
    public function loadUserByUsername($username) {
        $query = $this->_em->createQuery('SELECT u FROM AppBundle:User u WHERE u.username = :username');
        $query->setParameter('username', $username);

        try {
            $user = $query->getSingleResult();
        } catch (NoResultException $e) {
            $message = sprintf('Unable to find an active admin JPCoreBundle:User object identified by "%s".', $username);

            throw new UsernameNotFoundException($message, 0, $e);
        }

        return $user;
    }

    /**
     * Refresh user.
     *
     * @param UserInterface $user
     * @return UserInterface|void
     * @throws UnsupportedUserException
     */
    public function refreshUser(UserInterface $user) {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }

        return $this->find($user->getId());
    }

    /**
     * Supports class.
     *
     * @param string $class
     * @return bool|void
     */
    public function supportsClass($class) {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }
}
