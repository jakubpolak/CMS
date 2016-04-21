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
     * @param bool $asArray as array
     * @return array|Collection if $asArray true then an array is returned, otherwise a Collection
     *      is returned.
     */
    public function getByEntityAndEntityId(string $entity, int $entityId, bool $asArray = false) {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT t 
                FROM AppBundle:Translation t 
                JOIN AppBundle:TranslationMapper tm
                WHERE tm.entity = :entity AND tm.entityId = :entityId 
                ORDER BY tm.entity DESC, tm.entityId ASC
            ')->setParameters(['entity' => $entity, 'entityId' => $entityId]);

        return ($asArray === true) ? $query->getArrayResult() : $query->getResult();
    }
}
