<?php

namespace App\Observers;

use App\Models\Orders;
use App\Mail\OrderNew;
use App\Models\OrderActions;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class OrderObserver
{
    /**
     * Handle the Orders "created" event.
     *
     * @param  \App\Models\Orders  $orders
     * @return void
     */
    public function created(Orders $order)
    {
        $sendmail  = OrderActions::getActionForCurrency($order->currency_purchased_ISO,config('constants.actions.send_mail'));

        if(is_null($sendmail))
            return false;

        Mail::to($sendmail->value)->send(new OrderNew($order));
        return true;
    }

    /**
     * Handle the Orders "updated" event.
     *
     * @param  \App\Models\Orders  $orders
     * @return void
     */
    public function updated(Orders $order)
    {
        //
    }

    /**
     * Handle the Orders "deleted" event.
     *
     * @param  \App\Models\Orders  $orders
     * @return void
     */
    public function deleted(Orders $order)
    {
        //
    }

    /**
     * Handle the Orders "restored" event.
     *
     * @param  \App\Models\Orders  $orders
     * @return void
     */
    public function restored(Orders $order)
    {
        //
    }

    /**
     * Handle the Orders "force deleted" event.
     *
     * @param  \App\Models\Orders  $orders
     * @return void
     */
    public function forceDeleted(Orders $order)
    {
        //
    }
}
