<?php namespace App;

use DB;

abstract class Helper {

    /**
     * @param $code
     * @return string
     */
    public static function stockCode($code)
    {
            switch ($code) {
                    case 'A':
                            return "Uit voorraad";
                            break;
                    case 'B':
                            return "Binnen 24/48 uur mits voor 16.00 besteld";
                            break;
                    case 'C':
                            return "In overleg";
                            break;
                    case 'D':
                            return "Uitlopend";
                            break;
                    default:
                            return $code;
                            break;
            }
    }

    /**
     * @param $code
     * @return string
     */
    public static function price_per($code) {
            switch ($code) {
                    case 'Stk':
                            return "Stuk";
                            break;
                    case 'Mtr':
                            return "Meter";
                            break;
                    case 'Ds':
                            return "Doos";
                            break;
                    case 'Plt':
                            return "Plaat";
                            break;
                    case 'Stl':
                            return "Stel";
                            break;
                    case 'Mt2':
                            return "Meter&sup2;";
                            break;
                    case 'Str':
                            return "Streng";
                            break;
                    case 'Pr':
                            return "Paar";
                            break;
                    case 'Lgt':
                            return "Lengte";
                            break;
                    default:
                            return $code;
                            break;
            }
    }

    /**
     * Get all the discounts for a specific user
     *
     * @param $userId
     * @param int $group
     * @param int $product
     * @return array
     */
    public static function getProductDiscount($userId, $group = 0 , $product = 0)
    {
            $discountarray = array();

            // Add the default discounts
            $default_discounts = DB::table('discounts')->select('discount', 'User_Id', 'product')->where('table', 'VA-221')->whereNotIn('product', function($query) use ($userId) {
                    $query->select('product')
                            ->from('discounts')
                            ->where('table', 'VA-220')
                            ->where('User_Id', $userId);
            })->get();

            foreach ($default_discounts as $discount)
                    $discountarray[$discount->product] = preg_replace("/\,/", ".", $discount->discount);

            // Overwrite the defaults with the discounts linked to the product group
            $groupDiscounts = DB::table('discounts')->select('discount', 'User_Id', 'product')->where('table', 'VA-220')->where('User_Id', $userId)->get();

            foreach ($groupDiscounts as $discount)
                    $discountarray[$discount->product] = preg_replace("/\,/", ".", $discount->discount);

            // Overwrite the previous data with the discounts linked to the product number
            $productDiscounts = DB::table('discounts')->select('discount', 'product')->where('table', 'VA-261')->get();

            foreach ($productDiscounts as $discount)
                    $discountarray[$discount->product] = preg_replace("/\,/", ".", $discount->discount);

            // Overwrite the previous data with the discounts linked to the product number and to the customer
            $productDiscounts = DB::table('discounts')->select('discount', 'User_Id', 'product')->where('table', 'VA-260')->where('User_Id', $userId)->get();

            foreach ($productDiscounts as $discount)
                    $discountarray[$discount->product] = preg_replace("/\,/", ".", $discount->discount);

            if ($group === 0 && $product === 0)
                    return $discountarray;
            else
            {
                    if (!isset($discountarray[$product]) && !isset($discountarray[$group]))
                            App::abort(500, 'Geen korting gevonden voor product: ' . $product);
                    else
                            return (isset($discountarray[$product]) ? $discountarray[$product] : $discountarray[$group]);
            }
    }

    /**
     * Check if all the related products exist
     *
     * @param array
     * @return void
     */
    public static function checkRelatedProducts($products_with_related_products)
    {
        foreach ($products_with_related_products as $product => $relatedString) {

            foreach(explode(",", $relatedString) as $relatedProduct) {
                if (Product::where('number', $relatedProduct)->count() === 0) {
                    throw new \Exception("Product " . $product . " heeft een niet-bestaand gerelateerd product: " . $relatedProduct);
                }
            }

        }
    }

    /**
     * Calculate and return the number of minutes to the next 10 minute mark
     *
     * @return int
     */
    public static function timeToNextCronJob()
    {
        // Set the timezone
        date_default_timezone_set("Europe/Amsterdam");

        // This will magically set the $datetime to the next 10 minute mark
        $datetime = new \DateTime();
        $time_to_next_10_minutes = 10 - ($datetime->format("i") % 10);

        // return the difference between now and the next 10 minute mark
        return ceil($time_to_next_10_minutes);
    }
}
