<?php

namespace WTG\Soap;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use WTG\Services\AbstractSoapService;

/**
 * Soap service.
 *
 * @package     WTG
 * @subpackage  Soap
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Service extends AbstractSoapService
{
    public function getAllProduct()
    {
        return $this->client->__soapCall("GetProductV2", [
            "GetProductV2" => [
                "SecurityContext" => [
                    "SessionToken" => "",
                    "UserId" => config('soap.user'),
                    "Password" => config('soap.pass'),
                ],
                "AdminId" => config('soap.admin'),
                "ProfileId" => config('soap.profiles.product'),
                "ProductId" => "4011805",
                "UnitId" => "",
                "ContextDate" => Carbon::now()->format('Y-m-d')
            ]
        ]);
    }

    /**
     * Calls: GetProducts
     *
     * @soap
     * @param  int  $productsPerRequest
     * @param  int  $startFromIndex
     * @return GetProducts\Response
     */
    public function getAllProducts(int $productsPerRequest = 20, int $startFromIndex = 1)
    {
        /** @var GetProducts\Service $service */
        $service = app()->make(GetProducts\Service::class);
        return $service->handle($productsPerRequest, $startFromIndex);
    }

    /**
     * Calls: GetProductPrice
     *
     * @soap
     * @param  string  $sku
     * @param  float  $quantity
     * @param  string  $unit
     * @param  string  $customerId
     * @return GetProductPrice\Response
     */
    public function getProductPrice(string $sku, float $quantity, string $unit, string $customerId)
    {
        /** @var GetProductPrice\Service $service */
        $service = app()->make(GetProductPrice\Service::class);
        return $service->handle($sku, $quantity, $unit, $customerId);
    }

    /**
     * Calls: GetProductPricesAndStocks
     *
     * @soap
     * @param  Collection  $products
     * @param  string  $customerId
     * @return GetProductPricesAndStocks\Response
     */
    public function getProductPricesAndStocks(Collection $products, string $customerId)
    {
        /** @var GetProductPricesAndStocks\Service $service */
        $service = app()->make(GetProductPricesAndStocks\Service::class);
        return $service->handle($products, $customerId);
    }

    public function getProductStock()
    {
        return $this->client->__soapCall("GetProductStock", [
            "GetProductStock" => [
                "SecurityContext" => [
                    "SessionToken" => "",
                    "UserId" => config('soap.user'),
                    "Password" => config('soap.pass'),
                ],
                "AdminId" => config('soap.admin'),
                "ProfileId" => config('soap.profiles.priceAndStock'),
                "ProductId" => "1501333",
                "UnitId" => "LGT",
                "ContextDate" => Carbon::now()->format('Y-m-d')
            ]
        ]);
    }
}