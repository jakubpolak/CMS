<?php

namespace AppBundle\Service;

use AppBundle\Entity\Article;
use AppBundle\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;

/**
 * @author Jakub Polák
 */
class ArticleService {
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @var int
     */
    private $resultsPerPage;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager entity manager
     * @param int $resultsPerPage results per page
     */
    public function __construct(EntityManager $entityManager, int $resultsPerPage) {
        $this->em = $entityManager;
        $this->articleRepository = $this->em->getRepository('AppBundle:Article');
        $this->resultsPerPage = $resultsPerPage;
    }

    /**
     * Get articles for pagination.
     *
     * @param int $page
     * @return array
     */
    public function getForPagination(int $page): array {
        $firstResult = --$page * $this->resultsPerPage;
        return $this->articleRepository->getByFirstResultAndMaxResults($firstResult, $this->resultsPerPage);
    }

    /**
     * Delete article.
     *
     * @param Article $article
     */
    public function delete(Article $article) {
        if ($article !== null) {
            $this->em->remove($article);
            $this->em->flush();
        }
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
