<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserRouteController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userRoutes = $user->userRoute()->with('route')->latest()->paginate();
        return $this->success($userRoutes);
    }
}
