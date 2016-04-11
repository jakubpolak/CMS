<?php

namespace AppBundle\Repository;

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
    public function getEntityIds(string $entity): array {
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
    public function updateAttributeContent(string $attributeContent, string $attributeName, int $entityId, string $entity) {
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
     * Delete entries with entityId not in $idsOfEntitiesDQL or attribute not in $namesOfAttributes
     * and with name equal to $entity.
     * 
     * @param string $idsOfEntitiesDQL ids of entities DQL
     * @param string $namesOfAttributesDQL names of attributes DQL
     * @param string $entity entity
     */
    public function delete(string $idsOfEntitiesDQL, string $namesOfAttributesDQL, string $entity) {
        $this->getEntityManager()
            ->createQuery("
                DELETE FROM AppBundle:TranslationMapper tm 
                WHERE (tm.entityId NOT IN($idsOfEntitiesDQL) OR tm.attribute NOT IN($namesOfAttributesDQL)) AND tm.entity = :entity
            ")->setParameter('entity', $entity)
            ->execute();
    }

    /**
     * Get groups count.
     * 
     * @return int
     */
    public function getGroupsCount() {
        $result = $this->getEntityManager()
            ->createQuery('                
                SELECT COUNT(tm.id) 
                FROM AppBundle:TranslationMapper tm 
                GROUP BY tm.entityId, tm.entity                
            ')->getArrayResult();

        return count($result);
    }
}
