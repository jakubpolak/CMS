<?php

namespace AppBundle\Service;

/**
 * Cache service.
 *
 * @author Jakub Polák, Jana Poláková
 */
class CacheService {
    /**
     * @var string
     */
    private $cacheLocation;

    /**
     * Constructor
     *
     * @param string $cacheLocation
     */
    public function __construct(string $cacheLocation){
        $this->cacheLocation = $cacheLocation;
    }

    /**
     * Clear cache.
     *
     * @param string $dir
     */
    public function clearCache(string $dir = '') {
        $cache_dir = __DIR__ . $this->cacheLocation;

        if (is_dir($cache_dir) && basename($cache_dir) === 'cache') {
            $this->cc($cache_dir, "dev/$dir");
            $this->cc($cache_dir, "prod/$dir");
            $this->cc($cache_dir, "test/$dir");
        }
    }

    /**
     * Recursively remove directory
     *
     * @param string $dir
     */
    private function rrmdir(string $dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object !== '.' && $object !== '..') {
                    $o = $dir . '/' . $object;
                    if (filetype($o) === 'dir') {
                        $this->rrmdir($dir . '/' .$object);
                    } else {
                        unlink($o);
                    }
                }
            }

            reset($objects);
            rmdir($dir);
        }
    }

    /**
     * Remove cache from specified directory
     *
     * @param string $cache_dir
     * @param string $name
     */
    private function cc(string $cache_dir, string $name) {
        $d = $cache_dir . '/' . $name;
        if (is_dir($d)) {
            $this->rrmdir($d);
        }
    }
}