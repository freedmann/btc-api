<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BtcController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showError()
    {
        return response()->json(array(
            'status'  =>  'error',
            'code'    =>  '403',
            'message' =>   'Invalid token',
        ));
    }

    private function currencyRates($request, $ratesData)
    {
        $responseData=array();
        $currency = $request->input('currency');
        if (isset($currency))
            {
                if (isset($ratesData[$currency]))
                    $responseData[$currency]=$ratesData[$currency]["last"]+($ratesData[$currency]["last"]/100*2);
            }
        else
            {
                foreach ($ratesData as $rateItem){
                    $responseData[$rateItem['symbol']]=$rateItem["last"]+($rateItem["last"]/100*2);
                }
                asort($responseData);
            }
        if (count($responseData)==0) return $this->showError();

        return response()->json(array(
            'status'  =>  'success',
            'code'    =>  '200',
            'data' =>   $responseData,
        ));

    }

    private function currencyConvert($request, $ratesData)
    {
        $currencyFrom = $request->input('currency_from');
        $currencyTo = $request->input('currency_to');
        $convertValue = $request->input('value');
        $responseData=array();
        if ($convertValue<0.1)
            return $this->showError();

        if ($currencyFrom=='BTC')
            {
                if (!isset($ratesData[$currencyTo]["last"]))
                    return $this->showError();
                $rate=$ratesData[$currencyTo]["last"];
                $convertedValue=$convertValue*$rate;
                $comission=$convertValue*$rate/100*2;
                $convertedValue=$convertedValue+$comission;
                $convertedValue=round($convertedValue, 10);

            }
        elseif ($currencyTo=='BTC')
            {
                if (!isset($ratesData[$currencyFrom]["last"]))
                    return $this->showError();
                $rate=$ratesData[$currencyFrom]["last"];
                $convertedValue=$convertValue/$rate;
                $comission=$convertValue/$rate/100*2;
                $convertedValue=$convertedValue+$comission;
                $convertedValue=round($convertedValue, 10);

            }
        else
            return $this->showError();

        $responseData["currency_from"] = $currencyFrom;
        $responseData["currency_to"] = $currencyTo;
        $responseData["value"] = $convertValue;
        $responseData["converted_value"] = $convertedValue;
        $responseData["rate"] = $rate;

        return response()->json(array(
            'status'  =>  'success',
            'code'    =>  '200',
            'data' =>   $responseData,
        ));
    }

    public function callAPI(Request $request)
    {
        $token= request()->bearerToken();
        if ($token<>"S7dh-S8_2jdh76d35tsGDfs-sj_jdSD_88SdKj7-2d7G-2LMv_78AuI-3$31J7F")
            return $this->showError();
        $method = $request->input('method');
        $rates=json_decode(file_get_contents('https://blockchain.info/ticker'),true);
        if (!is_array($rates)) return $this->showError();
        if ($method=='rates'){
            return $this->currencyRates($request,$rates);
            }
        elseif ($method=='convert'){
            return $this->currencyConvert($request, $rates);
            }
        else
            return $this->showError();
    }
}
