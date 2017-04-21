<?php

namespace App\Services;

use DB;
use App\Models\Product;

/**
 * Helper Service
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class HelperService extends Service
{
    /**
     * Transform the product action type
     *
     * @param  string  $type
     * @return string
     */
    public function actionType($type)
    {
        switch ($type) {
            case 'clearance':
                return 'Opruiming';
                break;
            case 'action':
                return 'Actie';
                break;
            default:
                return $type;
                break;
        }
    }

    /**
     * Transform the stock code
     *
     * @param  string  $code
     * @return string
     */
    public function stockCode($code)
    {
        switch ($code) {
            case 'A':
                return 'Uit voorraad';
                break;
            case 'B':
                return 'Binnen 24/48 uur mits voor 16.00 besteld';
                break;
            case 'C':
                return 'In overleg';
                break;
            case 'D':
                return 'Uitlopend';
                break;
            default:
                return $code;
                break;
        }
    }

    /**
     * Transform the price_per code
     *
     * @param  string  $code
     * @return string
     */
    public function price_per($code)
    {
        switch ($code) {
            case 'Stk':
                return 'Stuk';
                break;
            case 'Mtr':
                return 'Meter';
                break;
            case 'Ds':
                return 'Doos';
                break;
            case 'Plt':
                return 'Plaat';
                break;
            case 'Stl':
                return 'Stel';
                break;
            case 'Mt2':
                return 'Meter&sup2;';
                break;
            case 'Str':
                return 'Streng';
                break;
            case 'Pr':
                return 'Paar';
                break;
            case 'Lgt':
                return 'Lengte';
                break;
            default:
                return $code;
                break;
        }
    }

    /**
     * Get all the discounts for a specific user.
     *
     * @param  int  $userId
     * @param  int  $group
     * @param  int  $product
     * @return float
     */
    public function getProductDiscount($userId, $group = 0, $product = 0)
    {
        $discounts = [];

        // Add the default discounts
        $default_discounts = DB::table('discounts')->select('discount', 'User_Id', 'product')->where('table', 'VA-221')->whereNotIn('product', function ($query) use ($userId) {
            $query->select('product')
                ->from('discounts')
                ->where('table', 'VA-220')
                ->where('User_Id', $userId);
        })->get();

        foreach ($default_discounts as $discount) {
            $discounts[$discount->product] = preg_replace("/\,/", '.', $discount->discount);
        }

        // Overwrite the defaults with the discounts linked to the product group
        $groupDiscounts = DB::table('discounts')->select('discount', 'User_Id', 'product')->where('table', 'VA-220')->where('User_Id', $userId)->get();

        foreach ($groupDiscounts as $discount) {
            $discounts[$discount->product] = preg_replace("/\,/", '.', $discount->discount);
        }

        // Overwrite the previous data with the discounts linked to the product number
        $productDiscounts = DB::table('discounts')->select('discount', 'product')->where('table', 'VA-261')->get();

        foreach ($productDiscounts as $discount) {
            $discounts[$discount->product] = preg_replace("/\,/", '.', $discount->discount);
        }

        // Overwrite the previous data with the discounts linked to the product number and to the customer
        $productDiscounts = DB::table('discounts')->select('discount', 'User_Id', 'product')->where('table', 'VA-260')->where('User_Id', $userId)->get();

        foreach ($productDiscounts as $discount) {
            $discounts[$discount->product] = preg_replace("/\,/", '.', $discount->discount);
        }

        if ($group === 0 && $product === 0) {
            return $discounts;
        } else {
            if (! isset($discounts[$product]) && ! isset($discounts[$group])) {
                abort(500, 'Geen korting gevonden voor product: '.$product);
            } else {
                return (float) ($discounts[$product] ?? $discounts[$group]);
            }
        }
    }

    /**
     * Check if all the related products exist.
     *
     * @param  array  $products_with_related_products
     * @throws \Exception
     * @return void
     */
    public function checkRelatedProducts($products_with_related_products)
    {
        foreach ($products_with_related_products as $product => $relatedString) {
            foreach (explode(',', $relatedString) as $relatedProduct) {
                if (Product::where('number', $relatedProduct)->count() === 0) {
                    throw new \Exception('Product '.$product.' heeft een niet-bestaand gerelateerd product: '.$relatedProduct);
                }
            }
        }
    }

    /**
     * Calculate and return the number of minutes to the next 10 minute mark.
     *
     * @return int
     */
    public static function timeToNextCronJob()
    {
        // Set the timezone
        date_default_timezone_set('Europe/Amsterdam');

        // This will magically set the $datetime to the next 10 minute mark
        $datetime = new \DateTime();
        $time_to_next_10_minutes = 10 - ($datetime->format('i') % 10);

        // return the difference between now and the next 10 minute mark
        return ceil($time_to_next_10_minutes);
    }

    /**
     * Convert bytes to KB/MB/etc.
     *
     * @param  int  $bytes
     * @param  string  $to
     * @throws \Exception
     * @return float
     */
    public static function convertByte(int $bytes = null, $to = 'MB')
    {
        if ($bytes === null) {
            return 0.0;
        }

        switch (strtoupper($to)) {
            case 'KB':
                $ret = $bytes / pow(10, 3);
                break;
            case 'MB':
                $ret = $bytes / pow(10, 6);
                break;
            case 'GB':
                $ret = $bytes / pow(10, 9);
                break;
            case 'TB':
                $ret = $bytes / pow(10, 12);
                break;
            default:
                throw new \Exception("Cannot convert {$bytes} to '{$to}'");
                break;
        }

        return round($ret, 2);
    }
}
