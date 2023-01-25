<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Currencies;
use App\Models\OrderActions as Actions;
use App\Models\Orders;
use App\Providers\OrderEvent;

class OrdersController extends Controller
{
    /**
     * Displays UI for purchasing currencies
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show()
    {
        $baseCurrencyISO    = 'USD';
        $baseCurrency       = Currencies::where('ISO', $baseCurrencyISO)->first();
        $exchangeRates      = Currencies::getExchangeRatesFor($baseCurrencyISO)->keyBy('to');

        return view('currency',['exchangeRates'=> $exchangeRates, 'baseCurrency' => $baseCurrency]);
    }

    /**
     * Lists all orders stored in db
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function list()
    {
        $orders = Orders::all();

        return response()->json([
            'data'    => $orders
        ], 200);
    }

    /**
     * Creates and stores new order
     *
     * @param Request $request HTTP request sent by browser or other HTTP client
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'buy'       => 'required|string|size:3',
            'amount'    => 'required|numeric|min:0',
            'for'       => 'required|string|size:3',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'errors'    => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $buysCurrency   = Currencies::where('ISO', $validated['buy'])->first();
        $forCurrency    = Currencies::where('ISO', $validated['for'])->first();
        $amount         = $validated['amount'];

        $errors =[];

        if(is_null($buysCurrency))
        {
            $erros['buy'] = [['Unkown currency: '.$validated['buy']]];
        }

        if(is_null($forCurrency))
        {
            $erros['for'] = [['Unkown currency: '.$validated['for']]];
        }

        if(count($errors))
        {
            return response()->json([
                'errors'    => $errors
            ], 422);
        }

        $exchangeRate   = Currencies::getExchangeRateFor($validated['for'], $validated['buy']);
        $discount       = Actions::getActionForCurrency($validated['buy'],config('constants.actions.discount'));

        $basePrice = $amount*$exchangeRate->rate;
        $surcharge = $basePrice*$exchangeRate->surcharge/100;
        $fullPrice = $basePrice+$surcharge;

        $discountAmount         = 0;
        $discountValue          = 0;

        if(!is_null($discount))
        {
            $discountAmount = $fullPrice*$discount->value/100;
            $discountValue  = $discount->value;

            $fullPrice = $fullPrice-$discountAmount;
        }

        $order = new Orders;
        $order->currency_purchased      = $buysCurrency->name;
        $order->currency_purchased_ISO  = $validated['buy'];

        $order->exchange_rate           = $exchangeRate->rate;
        $order->surcharge               = $exchangeRate->surcharge;
        $order->amount_purchased        = $amount;
        $order->currency_paid           = $forCurrency->name;
        $order->currency_paid_ISO       = $validated['for'];
        $order->amount_paid             = $fullPrice;
        $order->surcharge_amount        = $surcharge;

        $order->discount                = $discountValue;
        $order->discount_amount         = $discountAmount;

        $order->save();

        //should set try/catch and send report

        return response()->json([
            'message'    => 'Order created'
        ], 201);

    }
}
