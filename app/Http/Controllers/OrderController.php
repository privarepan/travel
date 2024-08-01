<?php

namespace App\Http\Controllers;

use App\Facades\HmPay;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $orders = $user->order()->latest()->get();
        return $this->success($orders);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->orderCompleted()->exists()) {
            return $this->error('您已加入会员，请不要重复下单');
        }
        $order = $user->order()->create([
            'amount' => 2999,
            'status' => 0,
            'order_no' => uniqid('yy',''),
        ]);

        $post = [
            'pay_way' => 'AUTO',
            'create_ip' => $request->ip(),
            'create_time' => now()->format('YmdHis'),
            'expire_time' => now()->addMinutes(10)->format('YmdHis'),
            'total_amount' => app()->environment('local', 'testing') ? 0.01 : $request->amount,
            'out_order_no' => $order->order_no,
            'body' => '2999自费套餐',
            'store_id' => '100001',
            'notify_url' => config('app.url').'/api/notify/order'
        ];

        $response = HmPay::tradePrecreate($post);
        if ($response->isSuccess()) {
            return $this->success(
                json_decode($response->json('data'),true)
            );
        }
        $order->notify();
        return $this->success($order);

//        return $this->error('充值下单失败', data: $response->json());

    }

    public function show($order_no)
    {
        $order = Order::whereOrderNo($order_no)->first();
        return $this->success($order,'请求成功');
    }

}
