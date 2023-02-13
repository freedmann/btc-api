<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Btc extends Model
{
    use HasFactory;
    public static function getRates($request, $ratesData)
    {
        $convert_commission = config('constants.convert_commission');

        $responseData=array();
        $currency = $request->input('currency');
        if (isset($currency))
        {
            if (isset($ratesData[$currency]))
                $responseData[$currency]=$ratesData[$currency]["last"]+($ratesData[$currency]["last"]/100*$convert_commission);
        }
        else
        {
            foreach ($ratesData as $rateItem){
                $responseData[$rateItem['symbol']]=$rateItem["last"]+($rateItem["last"]/100*$convert_commission);
            }
            asort($responseData);
        }
        return $responseData;
    }


public static function getConvert($currencyFrom, $currencyTo, $convertValue, $ratesData)
{
    $convert_commission = config('constants.convert_commission');
    $floor_convert= config('constants.floor_convert_value');
    $responseData = array();
    if ($convertValue<$floor_convert)
        return false;

    if ($currencyFrom=='BTC')
    {
        if (!isset($ratesData[$currencyTo]["last"]))
            return false;
        $rate=$ratesData[$currencyTo]["last"];
        $convertedValue=$convertValue*$rate;
        $comission=$convertValue*$rate/100*$convert_commission;
        $convertedValue=$convertedValue+$comission;
        $convertedValue=round($convertedValue, 10);

    }
    elseif ($currencyTo=='BTC')
    {
        if (!isset($ratesData[$currencyFrom]["last"]))
            return false;
        $rate=$ratesData[$currencyFrom]["last"];
        $convertedValue=$convertValue/$rate;
        $comission=$convertValue/$rate/100*$convert_commission;
        $convertedValue=$convertedValue+$comission;
        $convertedValue=round($convertedValue, 10);

    }
    $responseData["currency_from"] = $currencyFrom;
    $responseData["currency_to"] = $currencyTo;
    $responseData["value"] = $convertValue;
    $responseData["converted_value"] = $convertedValue;
    $responseData["rate"] = $rate;
    return $responseData;
}

}
