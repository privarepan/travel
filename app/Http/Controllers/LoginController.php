<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'key' => 'required',
            'mobile' => 'required|mobile',
            'code' => 'required',
            'captcha_code' => 'required|captcha_api:'. $request->key . ',math',
            'invite_code' => 'required|exists:users',
            'password' => ['required', Password::defaults(),'confirmed'],
        ];
        $msg = [
            'mobile.required' => '手机号 不能为空',
            'code.required' => '手机验证码 不能为空',
            'captcha_code.required' => '图形验证码 不能为空',
            'captcha_code.captcha_api' => '图形验证码 错误',
            'invite_code.required' => '邀请码 不能为空',
            'invite_code.exists' => '邀请码 不存在'
//            'invite_code.' => '邀请码 不能为空',
        ];
        $request->validate($rules,$msg);
        if (User::wherePhone($request->mobile)->exists()) {
            return $this->error('用户已存在');
        }
        $user = User::whereInviteCode($request->invite_code)->firstOrFail();

        $children = $user->children()->create([
            'level' => $user->level+1,
            'pid' => $user->getKey(),
            'role_lv' => 0,
            'is_member' => 0,
            'phone' => $request->mobile,
            'password' => $request->password,
            'invite_code' => User::getInviteCode(),
        ]);
        $children->path = $user->path.$children->id.'-';
        $children->save();
        return $this->success([
            'user' => $children,
        ]);
    }

    public function login(Request $request)
    {
        $rules = [
            'mobile' => ['required','mobile','exists:'.User::class.',phone'],
            'password' => 'required',
        ];
        $request->validate($rules);
        /**
         * @var $user User
         */
        $user = User::wherePhone($request->mobile)->first();
        if (!$user) {
            return $this->error('手机号或密码不正确');
        }
        if (Hash::check($request->password, $user->password)) {
            $token = $user->createToken('auth');
            return $this->success([
                'token' => $token->plainTextToken,
            ]);
        }
        return $this->error('手机号或密码不正确');
    }


    public function logout(Request $request)
    {
        $pass = $request->user()->tokens()->delete();
        return $pass ? $this->success() : $this->error();
    }

    public function resetPassword(Request $request)
    {
        $rules = [
            'key' => 'required',
            'mobile' => ['required','mobile'],
//            'code' => 'required',
            'captcha_code' => 'required|captcha_api:'. $request->key . ',math',
            'password' => ['required', Password::defaults(),'confirmed'],
            'password_confirmation' => 'required',
        ];
        $msg = [
            'mobile.required' => '手机号 不能为空',
//            'code.required' => '手机验证码 不能为空',
            'captcha_code.required' => '图形验证码 不能为空',
            'captcha_code.captcha_api' => '图形验证码 错误',
        ];
        $request->validate($rules,$msg);
        $user = User::where('phone', $request->mobile)->first();
        if ($user) {
            $user->password = $request->password;
            $user->save();
            return $this->success();
        }
        return $this->error('手机号，或者验证码错误');
    }
}
