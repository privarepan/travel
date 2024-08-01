<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_login()
    {

    }

    public function test_1()
    {
       $user = User::query()->forceCreate([
            'name' => 'root',
            'id_card' => fake()->unique()->creditCardNumber,
            'pid' => 0,
            'level' => 1,
            'role_lv' => 1,
            'path' => '-1-',
            'phone' => fake()->unique()->phoneNumber,
            'email' => fake()->unique()->email,
            'password' => fake()->password,
            'invite_code' => User::getInviteCode(),
            'status' => 1,
        ]);

        dd($user);
    }

    public function test_chiren()
    {
        /*$user = User::first();
        $users = $user->createChildren(5);
        $users->each->createChildren(5);*/
        User::whereLevel(3)->get()->each->createChildren(5);
    }

    public function test_give_member()
    {
        DB::listen(function (QueryExecuted $query){
            Str::of($query->sql)->replaceArray('?',$query->bindings)->dump();
        });
        /*User::find(150)
            ->up();*/
//        User::whereLevel(4)->whereIsMember(0)->get()->each->up();
//        User::wherePid(6)->take(5)->get()->each->up();
        User::find(137)->give();
    }

    public function test_pass()
    {
        dd(Password::defaults());
    }
}
