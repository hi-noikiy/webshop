<?php

namespace App\Services\DiscountFile;

use Illuminate\Support\Facades\Auth;

/**
 * Class AbstractGenerator
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
abstract class AbstractGenerator implements Generator
{
    const GROUP_DISCOUNT_TABLE = 'VA-220';
    const PRODUCT_DISCOUNT_TABLE = 'VA-260';
    const DEFAULT_GROUP_DISCOUNT_TABLE = 'VA-221';
    const DEFAULT_PRODUCT_DISCOUNT_TABLE = 'VA-261';

    /**
     * @var string
     */
    protected $text = '';

    /**
     * @var int
     */
    protected $customer;

    /**
     * @var string
     */
    protected $start_date;

    /**
     * CSVFileGenerator constructor.
     */
    public function __construct()
    {
        $this->customer = Auth::user()->company_id;
    }

    /**
     * Run the generator
     *
     * @return string
     */
    public function generate()
    {
        $this->addGroupDiscounts();

        $this->addDefaultGroupDiscounts();

        $this->addDefaultProductDiscounts();

        $this->addProductDiscounts();

        $this->prependHeaderLine();

        return $this->text;
    }
}