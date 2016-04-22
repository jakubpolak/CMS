<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Translation repository.
 *
 * @author Jakub Polák, Jana Poláková
 */
class TranslationRepository extends EntityRepository {
    /**
     * Delete entries with languageId not in $languageIdsDQL.
     *
     * @param string $languageIdsDQL not in ids of languages DQL
     */
    public function delete(string $languageIdsDQL) {
        $this->_em->createQuery("DELETE FROM AppBundle:Translation t WHERE t.language NOT IN($languageIdsDQL)")->execute();
    }

    /**
     * Get entries by entity and entity id.
     *
     * @param string $entity entity
     * @param int $entityId entity id
     * @param bool $asArray if true then array of arrays is returned,
     *      otherwise array of objects is returned
     * @return array if $asArray true then an array is returned, otherwise a Collection
     *      is returned.
     */
    public function getByEntityAndEntityId(string $entity, int $entityId, bool $asArray = false) {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT
                    tm.entityId,
                    t.content as attributeContent,
                    tm.entity,
                    tm.attribute,
                    tm.attributeType,
                    l.code as languageCode,
                    l.name as languageName
                FROM AppBundle:Translation t
                JOIN t.language l
                JOIN t.translationMapper tm
                WHERE tm.entity = :entity AND tm.entityId = :entityId
                ORDER BY l.isDefault ASC, l.id ASC, tm.entity DESC
            ')->setParameters(['entity' => $entity, 'entityId' => $entityId]);

        return ($asArray === true)
            ? $query->getArrayResult()
            : $query->getResult();
    }

    // TODO: Implement. @jpo
    public function getLimitedByLanguageId(int $languageId, int $firstResult, int $maxResults): array {
        return $this->_em
            ->createQuery('')
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResults)
            ->getArrayResult();
    }

    /**
     * Get entries by entity and entity id and language id.
     *
     * @deprecated
     * @param string $entity entity
     * @param int $entityId entity id
     * @param int $languageId language id
     * @param bool $asArray if true then array of arrays is returned,
     *      otherwise array of objects is returned
     * @return array
     */
    public function getByEntityAndEntityIdAndLanguageId(string $entity, int $entityId, int $languageId, bool $asArray = false) {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT
                    tm.entityId,
                    t.content as attributeContent,
                    tm.entity,
                    tm.attribute,
                    tm.attributeType                    
                FROM AppBundle:Translation t
                JOIN t.language l
                JOIN t.translationMapper tm
                WHERE tm.entity = :entity AND tm.entityId = :entityId AND t.languageId = :languageId
                ORDER BY tm.entity DESC
            ')->setParameters([
                'entity' => $entity,
                'entityId' => $entityId,
                'languageId' => $languageId
            ]);

        return ($asArray === true)
            ? $query->getArrayResult()
            : $query->getResult();
    }
}
