<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $orders = $user->order()->latest()->get();
        return $this->success($orders);
    }

    public function store()
    {
        $user = auth()->user();
        $order = $user->order()->create([
            'amount' => 2999,
            'status' => 0,
            'order_no' => uniqid('yy',''),
        ]);
        $order->notify();
        return $this->success($order);
    }

}
