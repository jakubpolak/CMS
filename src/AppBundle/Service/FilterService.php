<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

/**
 * Pagination service.
 *
 * @author Jakub Polák
 */
class FilterService {
    const COMPARATOR_EQUAL = '=';
    const COMPARATOR_GREATER_OR_EQUAL = '>=';
    const COMPARATOR_LOWER_OR_EQUAL = '<=';
    const COMPARATOR_UNEQUAL = '!=';
    const COMPARATOR_LIKE_BEGINNING = 'LIKE';
    const COMPARATOR_LIKE_END = 'LIKE';
    const COMPARATOR_LIKE_BEGINNING_AND_END = 'LIKE';

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
    }

    /**
     * Get pagination.
     *
     * @param int $firstResult first result
     * @param int $maxResults max results per page
     * @param string $entity entity name
     * @param array $orderBy order by [['column_name' => 'ASC'], ['column_name' => 'DESC'], ...]
     * @param array $filterBy filter by [['column_name' => ['value' => '', 'comparator' => '']]]
     * @return array
     */
    public function getPagination(int $firstResult, int $maxResults, string $entity, array $orderBy = [], array $filterBy = []): array {
        $query =  $this->em->getRepository($entity)
            ->createQueryBuilder('e')
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResults);

        if (count($orderBy) !== 0) {
            foreach ($orderBy as $column => $order) {
                $query->addOrderBy($column, $orderBy);
            }
        }

        if (count($filterBy) !== 0) {
            foreach ($filterBy as $column => $parameters) {
                $value = $parameters['value'];
                $comparator = $parameters['comparator'];
                $value = $this->transformValueByComparator($value, $comparator);
                $query->andWhere("e.{$column} {$comparator} :{$column}")->setParameter($column, $value);
            }
        }

        return $query->getQuery()
            ->useQueryCache(true)
            ->getResult()
        ;
    }

    /**
     * Get pages count.
     *
     * @param string $entity entity name
     * @param int $maxResults max results per page
     * @param array $filterBy filter results by
     * @return int
     */
    public function getPagesCount(string $entity, int $maxResults, array $filterBy = []): int {
        $entriesCount = $this->getEntriesCount($entity, $filterBy);
        return (int) ceil($entriesCount / $maxResults);
    }

    /**
     * Get count of entries.
     *
     * @param string $entity entity
     * @param array $filterBy filter by
     * @return int
     */
    public function getEntriesCount(string $entity, array $filterBy = []): int {
        $qb = $this->em->getRepository($entity)->createQueryBuilder('e')->select('COUNT(e.id)');

        if (count($filterBy) !== 0) {
            foreach ($filterBy as $column => $parameters) {
                $value = $parameters['value'];
                $comparator = $parameters['comparator'];
                $value = $this->transformValueByComparator($value, $comparator);
                $qb->andWhere("e.{$column} {$comparator} :{$column}")->setParameter($column, $value);
            }
        }

        return (int) $qb->getQuery()
            ->useQueryCache(true)
            ->getSingleScalarResult()
        ;
    }

    /**
     * Transform value by comparator.
     *
     * @param mixed $value value to be transformed
     * @param string $comparator comparator
     * @return mixed
     */
    private function transformValueByComparator($value, string $comparator) {
        switch ($comparator) {
            case self::COMPARATOR_LIKE_BEGINNING:
                $value = "%{$value}";
                break;
            case self::COMPARATOR_LIKE_BEGINNING_AND_END:
                $value = "%{$value}%";
                break;
            case self::COMPARATOR_LIKE_END:
                $value = "{$value}%";
                break;
        }

        return $value;
    }
}