<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;
use App\Models\Currencies;
use App\Models\Rates;

class CurrenciesController extends Controller
{
    /**
     * List of available currencies
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function list()
    {
        $currencies = Currencies::all();

        if($currencies->isEmpty())
        {
            return response()->json(['Currencies does not exists'],404);
        }

        foreach($currencies as $currency)
            $currency->makeHidden(['surcharge']);

        return response()->json(
            [
            'data' => $currencies
            ], 200);
    }

    /**
     * List of exchange rates for given base Currency
     *
     * @param String $baseCurrency 3-character ISO code for currency
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function exchangeRates($baseCurrency)
    {

        $currency = Currencies::getExchangeRatesFor($baseCurrency);

        if(is_null($currency))
        {
            return response()->json(['Currency supplied does not exists'],404);
        }

        return response()->json(
            [
            'data' => $currency
            ], 200);

    }

    /**
     * Retrieves exchange rate for stored currencies and updates for given baseCurrency
     *
     * @param String $baseCurrency 3-character ISO code
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function refresh($baseCurrency)
    {
        $baseCurrency = strtoupper($baseCurrency);
        //we need to send coma separated list of currencies for which we need quotes
        $currencies = Currencies::where('ISO','<>',$baseCurrency)->pluck('ISO');
        $currenciesSerialized = $currencies->join(',');

        $response = HTTP::withHeaders([
            'Content-Type'  => 'text/plain',
            'apikey'        => config('services.apilayer.key')
        ])
        ->get(config('services.apilayer.address'),[
            'source'    => $baseCurrency,
            'currencies'=> $currenciesSerialized,
        ]);

        $quotes = $response['quotes'];
        
        foreach($currencies as $currency)
        {
            $currKey = $baseCurrency.$currency;
            $rate   = $quotes[$currKey];

            $rateItem = Rates::firstOrNew(['from' => $baseCurrency, 'to' => $currency]);

            $rateItem->rate = $rate;
            $rateItem->save();            
        }

        return $this->exchangeRates($baseCurrency);
    }
}
