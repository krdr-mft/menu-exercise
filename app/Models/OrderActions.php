<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class OrderActions extends Model
{
    use HasFactory;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at','updated_at','id'];


    /**
     * Retrieves order action values
     *
     * @param String $currency 3-letter ISO currency code
     * @param String $action action name
     * @return Collection
     */
    public static function getActionForCurrency($currency, $action)
    {
        $orderActions = DB::table('currencies_order_actions as coa')
        ->leftJoin('order_actions as oa','coa.action','=','oa.id')
        ->select('coa.currency','oa.name as action','oa.parameter as parameter', 'coa.parameter as value')
        ->where('oa.name',$action)
        ->where('coa.currency',$currency)
        ->first();

        return $orderActions;
    }
}
