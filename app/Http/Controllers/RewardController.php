<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $rewards = $user->reward()->latest()->paginate();
        return $this->success($rewards);
    }


}
