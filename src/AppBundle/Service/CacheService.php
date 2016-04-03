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
        $cacheDir = __DIR__ . $this->cacheLocation;

        if (is_dir($cacheDir) && basename($cacheDir) === 'cache') {
            $this->cc($cacheDir, "dev/$dir");
            $this->cc($cacheDir, "prod/$dir");
            $this->cc($cacheDir, "test/$dir");
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
     * @param string $cacheDir
     * @param string $name
     */
    private function cc(string $cacheDir, string $name) {
        $d = $cacheDir . '/' . $name;
        if (is_dir($d)) {
            $this->rrmdir($d);
        }
    }
}