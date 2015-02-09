<?php

/**
 * @param $code
 * @return string
 */
function stockCode($code)
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
function price_per($code) {
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
function getProductDiscount($userId, $group = 0 , $product = 0)
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