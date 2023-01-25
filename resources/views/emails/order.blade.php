<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">

        <title>Order</title>
    </head>
    <body>
        <h2>Your order</h2>
        <table>
            <tr>
                <td>Currency purchased: </td>
                <td>{{ $order->currency_purchased }} ({{ $order->currency_purchased_ISO }})</td>
            </tr>
            <tr>
                <td>Paid in: </td>
                <td>{{ $order->currency_paid }} ({{ $order->currency_paid_ISO }})</td>
            </tr>
            <tr>
                <td>Exchange rate: </td>
                <td>{{ $order->exchange_rate }}</td>
            </tr>
            <tr>
                <td>Surcharge: </td>
                <td>{{ $order->surcharge }}%</td>
            </tr>
            <tr>
                <td>Amount purchased: </td>
                <td> {{ $order->amount_purchased }}{{ $order->currency_purchased_ISO }}</td>
            </tr>
            <tr>
                <td>Surcharge</td>
                <td>{{ $order->surcharge_amount }} </td>
            </tr>
            <tr>                
                <td>Discount</td><td>{{ $order->discount }}%</td>
            </tr>
            <tr>
                <td>Discount amount</td><td>{{ $order->discount_amount }}</td>
            </tr>
            <tr>
                <td>Total amount paid</td><td>{{ $order->amount_paid }}{{$order->currency_paid_ISO }}</td>
            </tr>
        </table>
    </body>
</html>