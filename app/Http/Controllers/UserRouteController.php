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

    public function store(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'route_id' => 'required',
            'start_at' => 'required|date',
            'mobile' => 'required|mobile',
            'name_first' => 'required',
            'name_second' => 'required',
            'id_card_first' => 'nullable|id_card',
            'id_card_second' => 'nullable|id_card',
            'remark' => 'nullable|string',
        ];
        $data = $request->validate($rules);
        if (!$user->is_member) {
            return $this->error('请先申请会员');
        }
        $user->loadCount('userRouteFinished');
        if ($user->user_route_finished_count >= 4) {
            return $this->error('预约次数上限');
        }
        $route = $user->userRouteFinished()->create($data);
        return $this->success($route);
    }
}
