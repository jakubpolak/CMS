<?php

namespace AppBundle\Service;

use AppBundle\Entity\Entity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * CRUD service.
 *
 * @author Jakub Polák, Jana Poláková
 */
abstract class CrudService {
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager entity manager
     */
    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    /**
     * Get repository.
     *
     * @return EntityRepository
     */
    public abstract function getRepository(): EntityRepository;

    /**
     * Save entity.
     *
     * @param Entity $entity
     */
    public function save(Entity $entity) {
        if ($entity->getId() === null){
            $this->em->persist($entity);
        }
        $this->em->flush();
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
     * Delete entity.
     *
     * @param Entity $entity
     */
    public function delete(Entity $entity) {
        $this->em->remove($entity);
        $this->em->flush();
    }
}
