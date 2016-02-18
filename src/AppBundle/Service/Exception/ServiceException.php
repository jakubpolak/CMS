<?php

namespace AppBundle\Service\Exception;

use Exception;

/**
 * Service exception.
 *
 * @author Jakub Polák, Jana Poláková
 */
class ServiceException extends \Exception {
    /**
     * Constructor.
     *
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct(string $message = '', int $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
