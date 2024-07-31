<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function authentication(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:1',
            'id_card' => 'required|id_card',
            'img_a' => 'required|string',
            'img_b' => 'required|string',
        ];
        $request->validate($rules);
        /**
         * @var $user User
         */
        $user = auth()->user();
        if ($user->state === 1) {
            return $this->error('请不要重复提交');
        }
        $user->update($request->only('name', 'id_card') + ['state' => 0]);
        $user->clearMediaCollection('authentication');
        $user->addMedia(Storage::path($request->img_a))->toMediaCollection('authentication');
        $user->addMedia(Storage::path($request->img_b))->toMediaCollection('authentication');
        return $this->success($user);
    }

    public function show()
    {
        return $this->success(['user' => auth()->user()]);
    }

    public function team()
    {
        /**
         * @var $user User
         */
        $user = auth()->user();
//        $count = $user->memberChildren()->count();
        //当前用户分红金额
        $user->loadSum('reward','amount');
        //团队业绩
        $path = $user->path;
        $team_amount = User::where('path', 'like', "%$path%")
            ->withSum('orderCompleted','amount')
            ->sum('order_completed_sum_amount');

        $team_total_amount = User::where('path', 'like', "%$path%")
//            ->whereHas('orderCompleted')
            ->count();

        $team_list = $user->memberChildren()
            ->withCount('memberChildren')
            ->get();

        return $this->success([
            'team_list' => $team_list,
            'total_num' => $team_total_amount,
            'user_reward_amount' => $user->reward_sum_amount,
            'team_total_amount' => $team_amount,
        ]);

    }



}
