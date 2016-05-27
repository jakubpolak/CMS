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
     * Get entity id by entity name.
     *
     * @param string $entityName entity
     * @return array
     */
    public function getEntityIdByEntity(string $entityName) : array {
        return $this->getEntityManager()
            ->createQuery('
                SELECT tm.entityId 
                FROM AppBundle:TranslationMapper tm 
                WHERE tm.entity = :entity 
                GROUP BY tm.entityId
            ')->setParameter('entity', $entityName)
            ->getArrayResult();
    }

    /**
     * Get by entity name.
     * 
     * @param string $entityName entity name
     * @return array
     */
    public function getByEntity(string $entityName) : array {
        return $this->getEntityManager()
            ->createQuery('SELECT tm FROM AppBundle:TranslationMapper tm WHERE tm.entity = :entity')
            ->setParameter('entity', $entityName)
            ->getResult();
    }

    /**
     * Update attribute content by entity id and attribute name and entity name.
     * 
     * @param string $attributeContent attribute content
     * @param string $attributeName attribute name
     * @param int $entityId entity id
     * @param string $entityName entity name
     */
    public function updateAttributeContent(
        string $attributeContent,
        string $attributeName,
        int $entityId,
        string $entityName
    ) {
        $this->getEntityManager()->createQuery('
            UPDATE AppBundle:TranslationMapper tm 
            SET tm.attributeContent = :attributeContent 
            WHERE tm.entityId = :entityId AND tm.attribute = :attributeName AND tm.entity = :entity
        ')->execute([
            'attributeContent' => $attributeContent,
            'attributeName' => $attributeName,
            'entityId' => $entityId,
            'entity' => $entityName
        ]);
    }

    /**
     * Delete by ids.
     * 
     * @param string $ids ids of entries
     */
    public function deleteByIds(string $ids) {
        $this->getEntityManager()
            ->createQuery("DELETE FROM AppBundle:TranslationMapper tm WHERE tm.id IN ($ids)")
            ->execute();
    }

    /**
     * Get ids of entries that do not have one of specified id or one of specified attributes
     * and that do have specified entity name.
     * 
     * @param string $idsOfEntities ids of entities
     * @param string $namesOfAttributes names of attributes
     * @param string $entityName entity name
     * @return array
     */
    public function getIdsToBeDeleted(string $idsOfEntities, string $namesOfAttributes, string $entityName) : array {
        return $this->getEntityManager()
            ->createQuery("
                SELECT tm.id FROM AppBundle:TranslationMapper tm 
                WHERE (tm.entityId NOT IN($idsOfEntities) 
                    OR tm.attribute NOT IN($namesOfAttributes)) 
                    AND tm.entity = :entity
            ")->setParameter('entity', $entityName)
            ->getArrayResult();
    }

    /**
     * Get count of entries grouped by entity id and entity.
     * 
     * @return int
     */
    public function getCountGroupedByEntityIdAndEntity() : int {
        $result = $this->getEntityManager()
            ->createQuery('                
                SELECT COUNT(tm.id) 
                FROM AppBundle:TranslationMapper tm 
                GROUP BY tm.entityId, tm.entity                
            ')->getArrayResult();

        return count($result);
    }
}
