<?php

namespace AppBundle\Helper;


class ReflectionHelper {
    /**
     * Get class name.
     *
     * @param object $obj
     * @return string
     */
    public static function getClassName($obj) {
        $className = get_class($obj);

        if (preg_match('@\\\\([\w]+)$@', $className, $matches)) {
            $className = $matches[1];
        }

        return $className;
    }
}
