<?php

namespace App\Http\Controllers;

use App\Facades\HmPay;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotifyController extends Controller
{
    public function order(Request $request)
    {
        Log::channel('hmp')->debug('callback',[
            'body' => $request->all(),
            'method' => $request->getMethod(),
        ]);

        if (!HmPay::verifySign($request->all())) {
            throw new \LogicException('签名未通过');
        }

        $order = Order::whereOrderNo($request->out_order_no)->firstOrFail();

        if ($order->isPaid()) {
            $order->notify();
        }

        return 'success';
    }
}
