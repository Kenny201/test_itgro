<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    protected $code;

    /**
     * @param $message
     * @param $code
     */
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
        $this->code = $code;
    }
}
