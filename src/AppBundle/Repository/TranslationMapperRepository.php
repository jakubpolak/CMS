<?php

namespace AppBundle\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;

/**
 * Translation mapper repository.
 *
 * @author Jakub Polák, Jana Poláková
 */
class TranslationMapperRepository extends EntityRepository {
    /**
     * Get entityId of entries with entity equal to $entity.
     *
     * @param string $entity entity
     * @return array
     */
    public function getEntityIdByEntity(string $entity): array {
        return $this->getEntityManager()
            ->createQuery('
                SELECT tm.entityId 
                FROM AppBundle:TranslationMapper tm 
                WHERE tm.entity = :entity 
                GROUP BY tm.entityId
            ')->setParameter('entity', $entity)
            ->getArrayResult();
    }

    /**
     * Update attributeContent to $attributeContent of an entry with attributeName equal to $attributeName
     * and attributeName equal to $attributeName and entityId equal to $entityId and entity equal to $entity.
     * 
     * @param string $attributeContent attribute content
     * @param string $attributeName attribute name
     * @param int $entityId entity id
     * @param string $entity entity
     */
    public function updateAttributeContent(
        string $attributeContent,
        string $attributeName,
        int $entityId,
        string $entity
    ) {
        $this->getEntityManager()
            ->createQuery('
                UPDATE AppBundle:TranslationMapper tm 
                SET tm.attributeContent = :attributeContent 
                WHERE tm.entityId = :entityId AND tm.attribute = :attributeName AND tm.entity = :entity
            ')->setParameters([
                'attributeContent' => $attributeContent,
                'attributeName' => $attributeName,
                'entityId' => $entityId,
                'entity' => $entity
            ])->execute();
    }

    /**
     * Delete entries with entityId not in $notInIdsOfEntitiesDQL or attribute not
     * in $notInNamesOfAttributesDQL and with name equal to $entity.
     * 
     * @param string $notInIdsOfEntitiesDQL not in ids of entities DQL
     * @param string $notInNamesOfAttributesDQL not in names of attributes DQL
     * @param string $entity entity
     */
    public function delete(
        string $notInIdsOfEntitiesDQL,
        string $notInNamesOfAttributesDQL,
        string $entity
    ) {
        $this->getEntityManager()
            ->createQuery("
                DELETE FROM AppBundle:TranslationMapper tm 
                WHERE (tm.entityId NOT IN($notInIdsOfEntitiesDQL) OR tm.attribute NOT IN($notInNamesOfAttributesDQL)) AND tm.entity = :entity
            ")->setParameter('entity', $entity)
            ->execute();
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
                SELECT tm 
                FROM AppBundle:TranslationMapper tm 
                WHERE tm.entity = :entity AND tm.entityId = :entityId 
                ORDER BY tm.entity DESC, tm.entityId ASC
            ')->setParameters(['entity' => $entity, 'entityId' => $entityId]);

        return ($asArray === true) ? $query->getArrayResult() : $query->getResult();
    }

    /**
     * Get pagination.
     *
     * @param int $firstResult first result
     * @param int $maxResults max results
     * @return array
     */
    public function getLimited(int $firstResult, int $maxResults): array {
        return $this->getEntityManager()
            ->createQuery('
                SELECT tm
                FROM AppBundle:TranslationMapper tm
                ORDER BY tm.entity DESC, tm.entityId ASC
            ')->setFirstResult($firstResult)
            ->setMaxResults($maxResults)
            ->getResult();
    }

    /**
     * Get count of entries.
     *
     * @return int
     */
    public function getCount(): int {
        return (int) $this->getEntityManager()
            ->createQuery('SELECT COUNT(tm.id) FROM AppBundle:TranslationMapper tm')
            ->getSingleScalarResult();
    }

    /**
     * Get grouped by entity
     *
     * @param int $firstResult first result
     * @param int $maxResults max results
     * @return array
     */
    public function getLimitedAndGroupedByEntityIdAndEntity(int $firstResult, int $maxResults): array {
        return $this->getEntityManager()
            ->createQuery('
                SELECT tm 
                FROM AppBundle:TranslationMapper tm                  
                GROUP BY tm.entity, tm.entityId
                ORDER BY tm.entity DESC, tm.entityId ASC
            ')->setFirstResult($firstResult)
            ->setMaxResults($maxResults)
            ->getResult();
    }

    /**
     * Get groups count.
     * 
     * @return int
     */
    public function getCountGroupedByEntityIdAndEntity(): int {
        $result = $this->getEntityManager()
            ->createQuery('                
                SELECT COUNT(tm.id) 
                FROM AppBundle:TranslationMapper tm 
                GROUP BY tm.entityId, tm.entity                
            ')->getArrayResult();

        return count($result);
    }
}
