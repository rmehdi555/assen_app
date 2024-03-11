<?php

namespace App\Helpers;

use App\Models\Exchanges;
use Illuminate\Support\Facades\Config;

class Convertors
{
    public static function weightConverter($weighttype, $weightvalue): float|int|string
    {
        $returnee = "";
        switch ($weighttype) {
            case 'گرم':
                $returnee = $weightvalue * 1;
                break;
            case 'کیلوگرم':
                $returnee = $weightvalue * 1000;
                break;
            case 'پوند':
                $returnee = $weightvalue * 454;
                break;
            case 'انس':
                $returnee = $weightvalue * 28;
                break;
        }
        return $returnee;
    }


    public static function extractWeightUnit($weightString)
    {

        $unit = 'notfound';

        if (strpos($weightString, 'pound') or strpos($weightString, 'Pound'))
            $unit = 'پوند';
        elseif (strpos($weightString, 'ounce') or strpos($weightString, 'Ounce'))
            $unit = 'انس';
        elseif (strpos($weightString, 'Kg') or strpos($weightString, 'Kilogram') or strpos($weightString, 'kg'))
            $unit = 'کیلوگرم';
        elseif (strpos($weightString, 'grams') or strpos($weightString, 'Grams') or strpos($weightString, 'gr') or strpos($weightString, 'g'))
            $unit = 'گرم';

        return $unit;
    }

    public static function datetocode()
    {
        $dts = date('Y-m-d H:i:s');
        $num = rand(0, 999);
        $dts = str_replace(str_split(' -:'), '', $dts) + $num;

        return $dts;
    }

    public static function changePrice($price, $exchange = 'Toman')
    {
        if ($exchange == 'IRR') {
            $resultPrice = $price;
        } elseif ($exchange == 'Toman' and $price != 0) {
            $resultPrice = round($price / 10, 0);
        } elseif ($exchange == 'USD' and $price != 0) {
            $resultPrice = round($price / Exchanges::find(1)->value, 2);
        } elseif ($exchange == 'EUR' and $price != 0) {
            $resultPrice = round($price / Exchanges::find(2)->value, 2);
        } else
            $resultPrice = 0;

        return $resultPrice;
    }

}
