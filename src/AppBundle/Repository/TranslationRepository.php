<?php

namespace AppBundle\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;

/**
 * Translation repository.
 *
 * @author Jakub Polák, Jana Poláková
 */
class TranslationRepository extends EntityRepository {
    /**
     * Delete entries with language id not among language ids.
     *
     * @param string $languageIds not in ids of languages DQL
     */
    public function delete(string $languageIds) {
        $this->getEntityManager()
            ->createQuery("
                DELETE FROM AppBundle:Translation t 
                WHERE t.language NOT IN($languageIds)
            ")->execute();
    }

    /**
     * Get entries by language id.
     *
     * @param int $languageId language id
     * @return array
     */
    public function getByLanguageId(int $languageId) : array {
        return $this->getEntityManager()
            ->createQuery('
                SELECT t FROM AppBundle:Translation t 
                WHERE t.language = :language
            ')->setParameter('language', $languageId)
            ->getResult();
    }

    /**
     * Get entries by entity and entity id.
     *
     * @param string $entity entity
     * @param int $languageId language id
     * @param int $entityId entity id
     * @param bool $asArray if true then array of arrays is returned, otherwise array of objects is returned
     * @return array if $asArray true then an array is returned, otherwise a Collection is returned.
     */
    public function getByLanguageIdAndEntityAndEntityId(
        int $languageId,
        string $entity,
        int $entityId,
        bool $asArray = false
    ) {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT
                    tm.entityId,
                    t.content as attributeContent,
                    tm.entity,
                    tm.attribute,
                    tm.attributeType 
                FROM AppBundle:Translation t 
                JOIN t.translationMapper tm
                WHERE tm.entity = :entity 
                    AND tm.entityId = :entityId 
                    AND t.language = :languageId
                ORDER BY tm.entity DESC
            ')->setParameters(['languageId' => $languageId, 'entity' => $entity, 'entityId' => $entityId]);

        return ($asArray === true)
            ? $query->getArrayResult()
            : $query->getResult();
    }

    /**
     * Get entries by language id limited by first result and max results.
     * 
     * @param int $languageId language id
     * @param int $firstResult first result
     * @param int $maxResults max results
     * @return array
     */
    public function getLimitedByLanguageId(int $languageId, int $firstResult, int $maxResults) : array {
        return $this->getEntityManager()
            ->createQuery('
                SELECT t 
                FROM AppBundle:Translation t
                JOIN t.translationMapper tm
                WHERE t.language = :languageId
                GROUP BY tm.entityId
            ')->setParameter('languageId', $languageId)
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResults)
            ->getResult();
    }

    /**
     * Get entries by entity and entity id.
     *
     * @param string $entity entity name
     * @param int $entityId entity id
     * @param int $languageId language id
     * @param bool $asArray as array
     * @return array|Collection if $asArray true then an array is returned, otherwise a Collection
     *      is returned.
     */
    public function getByEntityAndEntityIdAndLanguageId(
        string $entity,
        int $entityId,
        int $languageId,
        bool $asArray = false
    ) {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT t.id, t.content, tm.attribute, tm.attributeType, tm.entityId, l.code
                FROM AppBundle:Translation t 
                JOIN t.translationMapper tm
                JOIN t.language l
                WHERE tm.entity = :entity AND tm.entityId = :entityId AND t.language = :languageId                 
                ORDER BY l.isDefault DESC, t.translationMapper ASC, tm.entityId ASC
            ')->setParameters([
                'entity' => $entity, 
                'entityId' => $entityId, 
                'languageId' => $languageId
            ]);

        return ($asArray === true)
            ? $query->getArrayResult()
            : $query->getResult();
    }

    /**
     * Delete by translation mapper ids.
     * 
     * @param string $translationMapperIds translation mapper ids.
     */
    public function deleteByTranslationMapperIds(string $translationMapperIds) {
        $this->getEntityManager()
            ->createQuery("DELETE FROM AppBundle:Translation t WHERE t.translationMapper IN ($translationMapperIds)")
            ->execute();
    }
}
