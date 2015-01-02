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

function getProductKorting($group = 0 , $product = 0, $userId)
{
        $discountarray = array();

        $default_discounts = DB::table('discounts')->select('discount', 'User_Id', 'product')->where('product', 'NOT IN', "(SELECT product FROM 'discounts' WHERE table = 'VA-220' AND User_Id = '" . $userId ."')")->get();

        foreach ($default_discounts as $discount)
                $discountarray[$discount->product] = preg_replace("/\,/", ".", $discount->discount);

        $groupDiscounts = DB::table('discounts')->select('discount', 'User_Id', 'product')->where('table', 'VA-220')->get();

        foreach ($groupDiscounts as $discount)
                $discountarray[$discount->product] = preg_replace("/\,/", ".", $discount->discount);

        $productDiscounts = DB::table('discounts')->select('discount', 'User_Id', 'product')->where('table', 'VA-260')->get();

        foreach ($productDiscounts as $discount)
                $discountarray[$discount->product] = preg_replace("/\,/", ".", $discount->discount);

        if ($group === 0 && $product === 0)
                return $discountarray;
        else
                return (isset($discountarray[$product]) ? $discountarray[$product] : $discountarray[$group]);
}