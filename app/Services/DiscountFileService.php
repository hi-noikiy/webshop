<?php

namespace App\Services;

use App\Services\DiscountFile\CSVFileGenerator;
use App\Services\DiscountFile\ICCFileGenerator;

/**
 * Class DiscountFileService
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class DiscountFileService extends Service
{
    /**
     * ICC file generator
     *
     * @return resource
     */
    public function icc()
    {
        $generator = new ICCFileGenerator;

        return $generator->generate();
    }

    /**
     * ICC file generator
     *
     * @return resource
     */
    public function csv()
    {
        $generator = new CSVFileGenerator;

        return $generator->generate();
    }
}