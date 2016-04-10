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
     * Get entity id by entity.
     *
     * @param string $entity entity
     * @return array
     */
    public function getEntityIdByEntity(string $entity): array {
        return $this->getEntityManager()
            ->createQuery('SELECT tm.entityId FROM AppBundle:TranslationMapper tm WHERE tm.entity = :entity GROUP BY tm.entityId')
            ->setParameter('entity', $entity)
            ->getArrayResult();
    }

    public function deleteByEntity($idsOfEntitiesDQL, $namesOfAttributesDQL, $entity) {
        $this->getEntityManager()
            ->createQuery('DELETE FROM AppBundle:TranslationMapper tm WHERE tm.entityId NOT IN(:idsOfEntitiesDQL) OR tm.attribute NOT IN(:namesOfAttributesDQL) AND tm.entity = :entity')
            ->setParameters([
                'entity' => $entity,
                'idsOfEntitiesDQL' => $idsOfEntitiesDQL,
                'namesOfAttributesDQL' => $namesOfAttributesDQL,
            ])->execute();
    }
}