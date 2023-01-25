<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Currencies extends Model
{
    use HasFactory;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at','updated_at','id'];

    /**
     * Retrieves list of exchange rates
     *
     * @param String $forCurrency   3-character ISO code, base currency
     * @param String $toCurrency    3-character ISO code, purchasing currency
     * @return Collection
     */
    public static function getExchangeRatesFor($forCurrency, $toCurrency = NULL)
    {
        $currency = DB::table('rates')
        ->leftJoin('currencies as c_from','rates.from','=','c_from.ISO')
        ->leftJoin('currencies as c_to','rates.to','=','c_to.ISO')
        ->select('c_from.name as name_from', 'rates.from', 'c_to.name as name_to', 'rates.to', 'rates.rate', 'c_to.surcharge')
        ->where('rates.from',$forCurrency);

        if(!is_null($toCurrency))
        {
            $currency = $currency->where('rates.to', $toCurrency);        
        }

        return $currency->get();
    }


    /**
     * Retrieves quote for given currencies
     *
     * @param String $forCurrency   3-character ISO code, base currency
     * @param String $toCurrency    3-character ISO code, purchasing currency
     * @return Collection
     */
    public static function getExchangeRateFor($forCurrency, $toCurrency)
    {
        $currency = DB::table('rates')
        ->leftJoin('currencies as c_from','rates.from','=','c_from.ISO')
        ->leftJoin('currencies as c_to','rates.to','=','c_to.ISO')
        ->select('c_from.name as name_from', 'rates.from', 'c_to.name as name_to', 'rates.to', 'rates.rate', 'c_to.surcharge')
        ->where('rates.from',$forCurrency);

        if(!is_null($toCurrency))
        {
            $currency = $currency->where('rates.to', $toCurrency);        
        }

        return $currency->first();
    }

}