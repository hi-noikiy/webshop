<?php

namespace WTG\Soap\GetProductPrice;

use Exception;
use WTG\Soap\AbstractService;

/**
 * GetProductPrice service.
 *
 * @package     WTG\Soap
 * @subpackage  GetProductPrice
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Service extends AbstractService
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var string
     */
    protected $sku;

    /**
     * @var float
     */
    protected $quantity;

    /**
     * @var string
     */
    protected $customerId;

    /**
     * @var string
     */
    protected $unit;

    /**
     * Service constructor.
     */
    public function __construct()
    {
        $this->request = app()->make(Request::class);
        $this->response = app()->make(Response::class);
    }

    /**
     * Run the service.
     *
     * @param  string  $sku
     * @param  float  $quantity
     * @param  string  $unit
     * @param  string  $customerId
     * @return Response
     */
    public function handle(string $sku, float $quantity, string $unit, string $customerId)
    {
        $this->sku = $sku;
        $this->quantity = $quantity;
        $this->unit = $unit;
        $this->customerId = $customerId;

        try {
            $this->buildRequest();
            $soapResponse = $this->sendRequest(
                "GetProductPriceV2",
                $this->request
            );
            $this->buildResponse($soapResponse);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        return $this->response;
    }

    /**
     * Build the request.
     *
     * @return void
     */
    protected function buildRequest()
    {
        $this->request->amount = $this->quantity;
        $this->request->debtorId = $this->customerId;
        $this->request->productId = $this->sku;
        $this->request->unitId = $this->unit;
    }

    /**
     * Build the response.
     *
     * @param  object  $soapResponse
     * @return void
     * @throws Exception
     */
    protected function buildResponse($soapResponse)
    {
        dd($soapResponse);
    }
}