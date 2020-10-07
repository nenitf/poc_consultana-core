<?php

namespace Core\Exceptions;

class CoreException extends \RuntimeException {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
