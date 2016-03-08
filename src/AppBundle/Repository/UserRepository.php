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
class UserRepository extends EntityRepository {
    /**
     * Get password and salt by id.
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id) : array {
        return $this->_em->createQuery('SELECT u FROM AppBundle:User u WHERE u.id = :id')
            ->setParameter('id', $id)
            ->useQueryCache(true)
            ->getArrayResult()
        ;
    }
}
