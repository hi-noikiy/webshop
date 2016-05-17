<?php namespace App\Exceptions;

class InvalidColumnCountException extends \Exception {

    public function __construct($line, $got, $expect, $code = 0, \Exception $previous = null)
    {
        $message = "Invalid column count at line: {$line}. Got {$got}, expected {$expect}";

        parent::__construct($message, $code, $previous);
    }

}
