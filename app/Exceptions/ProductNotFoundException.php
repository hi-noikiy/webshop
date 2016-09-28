<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ProductNotFoundException.
 */
class ProductNotFoundException extends HttpException
{

    /**
     * ProductNotFoundException constructor.
     *
     * @param  string  $id
     * @param  \Exception|null  $previous
     * @param  array  $headers
     * @param  int  $code
     */
    public function __construct($id, \Exception $previous = null, array $headers = [], $code = 0)
    {
        $message = "Geen product gevonden met nummer '{$id}'";

        parent::__construct(404, $message, $previous, $headers, $code);
    }

}
