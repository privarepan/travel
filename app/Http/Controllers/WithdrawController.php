<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $withdraws = $user->withdraw()->latest()->get();
        return $this->success($withdraws);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'amount' => 'required|numeric|min:1',
            'pay_type' => 'required|in:0,1',
            'account' => 'required',
            'name' => 'required',
            'bank_name' => 'required_if:pay_type,1',
        ];
        $data = $request->validate($rules);
        if ($user->canWithdraw($request->amount)) {
            $withdraw = $user->withdrawing($data);
            return $this->success($withdraw,'提现成功，请等待审批');
        }
        return $this->error('余额不足');
    }
}
