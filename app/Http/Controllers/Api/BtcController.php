<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Btc;


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
        $responseData=Btc::getRates($request, $ratesData);
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
        $responseData = Btc::getConvert($currencyFrom, $currencyTo, $convertValue, $ratesData);

        if (!is_array($responseData))
            return $this->showError();

        return response()->json(array(
            'status'  =>  'success',
            'code'    =>  '200',
            'data' =>   $responseData,
        ));
    }

    public function callAPI(Request $request)
    {
        $private_token=config('constants.api_key');
        $token= request()->bearerToken();
        if ($token<>$private_token)
            return $this->showError();
        $method = $request->input('method');
        $ticker_url=config('constants.btc_ticker_url');
        $rates=json_decode(file_get_contents($ticker_url),true);
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
