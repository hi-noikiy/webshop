<?php namespace App\Exceptions;

class InvalidColumnCountException extends \Exception {

    public function __construct($line, $code = 0, \Exception $previous = null)
    {
        $message = "Invalid column count at line: {$line}";

        parent::__construct($message, $code, $previous);
    }

}