<?php

namespace AppBundle\Service;

use AppBundle\Entity\Article;
use AppBundle\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;

/**
 * Article service.
 *
 * @author Jakub Polák
 */
class ArticleService {
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager entity manager
     */
    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
        $this->articleRepository = $this->em->getRepository('AppBundle:Article');
    }

    /**
     * Get all articles.
     *
     * @return array
     */
    public function getAll(): array {
        return $this->articleRepository->findAll();
    }

    /**
     * Delete article.
     *
     * @param Article $article
     */
    public function delete(Article $article) {
        $this->em->remove($article);
        $this->em->flush();
    }

    /**
     * Save an article.
     *
     * @param Article $article
     */
    public function save(Article $article) {
        if ($article->getId() === null) {
            $this->em->persist($article);
        }
        $this->em->flush();
    }
}
