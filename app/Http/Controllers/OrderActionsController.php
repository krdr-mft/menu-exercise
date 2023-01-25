<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\OrderActions;
use App\Models\Currencies;

class OrderActionsController extends Controller
{
    private const DISCOUNT_ACTION   = 'discount';
    private const SEND_MAIL_ACTION  = 'sendmail';

    /**
     * Retrieves list of avaliable order actions
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function list()
    {
        $orderActions = DB::table('currencies_order_actions as coa')
                    ->leftJoin('order_actions as oa','coa.action','=','oa.id')
                    ->select('coa.currency','oa.name as action','oa.parameter as parameter', 'coa.parameter as value')
                    ->get();

        if($orderActions->isEmpty())
        {
            return response()->json('No actions defined',404);
        }

        return response()->json(
            [
                'data' => $orderActions
            ]
        );
    }

    /**
     * Returns list of Order Action types possible in the system
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function listTypes()
    {
        $actionTypes = OrderActions::all();

        if($actionTypes->isEmpty())
        {
            return response()->json('Action types not defined',404);
        }

        return response()->json(
            [
                'data' => $actionTypes
            ]
        );
    }

    /**
     * Updates order action for given type and currency
     *
     * @param Request $request HTTP Request
     * @param String $action name of action type
     * @param String $currency 3-character ISO code
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(Request $request, $action, $currency)
    {
        $actionType = OrderActions::where('name',$action)->first()->makeVisible(['id']);

        if(is_null($actionType))
        {
            return response()->json('Unkown action type',400);
        }

        $currency = Currencies::where('ISO',strtoupper($currency))->first();

        if(is_null($currency))
        {
            return response()->json('Unkown currency',400);
        }


        if($action === self::SEND_MAIL_ACTION)
        {
            $validator = Validator::make($request->all(), [
                'parameter' => 'required|email:rfc,dns'
            ]);
        }
        elseif($action === self::DISCOUNT_ACTION)
        {
            $validator = Validator::make($request->all(), [
                'parameter' => 'required|Integer'
            ]);
        }
        else
        {
            return response()->json('Not found', 404);
        }

        if ($validator->fails()) {
            return response()->json('Incorrect parameter value', 400);
        }

        $validated = $validator->safe()->only(['parameter']);

        $affected = DB::table('currencies_order_actions')
            ->where('action',$actionType->id)
            ->where('currency', $currency->ISO)
            ->update([
                'parameter' => $validated['parameter'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return response()->json('Order action updated', 201);
    }
}
