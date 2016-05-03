<?php

namespace AppBundle\Helper;

use Doctrine\ORM\EntityManager;

/**
 * DQL Helper.
 *
 * @author Jakub Polák, Jana Poláková
 */
class DqlHelper {
    /**
     * Get ids of entities by entity name.
     *
     * @param EntityManager $entityManager entity manager
     * @param string $entityName entity name
     * @return array
     */
    public function getIds(EntityManager $entityManager, string $entityName): array {
        return $entityManager->createQuery("SELECT e.id FROM AppBundle:$entityName e")->getArrayResult();
    }

    /**
     * Get attributes with alias as DQL.
     *
     * @param array $namesOfAttributes names of entity attributes
     * @return string
     */
    public function getAttributesWithAliasDQL(array $namesOfAttributes): string {
        $result = 'e.id,';
        foreach ($namesOfAttributes as $attribute) {
            $result .= "e.{$attribute},";
        }
        return substr($result, 0, - 1);
    }

    /**
     * Convert two dimensional array to one dimensional array.
     *
     * @param array $twoDimension two dimensional array
     * @param string $key array key of second dimension of two dimensional array
     * @return array
     */
    public function convertToVector(array $twoDimension, string $key): array {
        $oneDimension = array();
        foreach ($twoDimension as $entry) {
            $oneDimension[] = $entry[$key];
        }
        return $oneDimension;
    }

    /**
     * Convert array to a string.
     *
     * @param array $array array of values to be converted to a string
     * @param string $delimiter string to delimit array values by
     * @param string $wrap string to wrap array values in
     * @return string
     */
    public function convertToString(array $array, string $delimiter = ',', string $wrap = ''): string {
        $result = '';
        foreach ($array as $value) {
            $result .= "{$wrap}{$value}{$wrap}{$delimiter}";
        }
        return substr($result, 0, - 1);
    }
}
