<?php

namespace AppBundle\Service;

use AppBundle\Entity\Article;
use AppBundle\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Article service.
 *
 * @author Jakub Polák, Jana Poláková
 */
class ArticleService extends CrudService {
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager entity manager
     */
    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager);
        $this->articleRepository = $this->em->getRepository('AppBundle:Article');
    }

    /**
     * Get repository.
     *
     * @return EntityRepository
     */
    public function getRepository(): EntityRepository {
        return $this->articleRepository;
    }
}
