<?php

namespace App\Exceptions;

class InvalidColumnCountException extends \Exception
{
    /**
     * InvalidColumnCountException constructor.
     *
     * @param  string  $line
     * @param  int  $got
     * @param  \Exception  $expect
     * @param  int  $code
     * @param  \Exception|null  $previous
     */
    public function __construct($line, $got, $expect, $code = 0, \Exception $previous = null)
    {
        $message = "Invalid column count at line: {$line}. Got {$got}, expected {$expect}";

        parent::__construct($message, $code, $previous);
    }
}
