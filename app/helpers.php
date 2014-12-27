<?php

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

function getProductKorting($group, $number, $userId)
{
        return 0;
}