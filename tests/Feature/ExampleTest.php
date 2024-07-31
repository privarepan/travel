<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Facades\HmPay;
use App\Models\User;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_pay()
    {
        $payWay = "AUTO";
        $createIp = "127.0.0.1";//生产环境使用真实ip
        $createTime = date("YmdHis");
        $expireTime = date("YmdHis", strtotime("+10 minute"));//过期时间
        $totalAmount = 1.00;
        $outOrderNo = Str::orderedUuid();
        //商品详情 -- 按实际填写 或不填不上送
        $goodsDetail1 = [];
        $goodsDetail1['goods_id'] = 'a01';
        $goodsDetail1['goods_name'] = 'a1';
        $goodsDetail1['quantity'] = '1';
        $goodsDetail1['price'] = '0.01';

        $goodsDetail2 = [];
        $goodsDetail2['goods_id'] = 'a02';
        $goodsDetail2['goods_name'] = 'a2';
        $goodsDetail2['quantity'] = '1';
        $goodsDetail2['price'] = '0.99';
        $goodsDetail = [$goodsDetail1, $goodsDetail2];
        //订单详情
        $body = "无人超市";
        //商户交易门店
        $storeId = "100001";
        //终端信息

        $notifyUrl = null;

        $tradePrecreate['pay_way'] = $payWay;
        $tradePrecreate['create_ip'] = $createIp;
        $tradePrecreate['create_time'] = $createTime;
        $tradePrecreate['expire_time'] = $expireTime;
        $tradePrecreate['total_amount'] = $totalAmount;
        $tradePrecreate['out_order_no'] = $outOrderNo;
        $tradePrecreate['goods_detail'] = $goodsDetail;
        $tradePrecreate['body'] = $body;
        $tradePrecreate['store_id'] = $storeId;
        $tradePrecreate['notify_url'] = $notifyUrl;
        $response = HmPay::tradePrecreate($tradePrecreate);
        dd($response);
    }

    public function test_1()
    {
        DB::listen(function (QueryExecuted $executed){
            Str::of($executed->sql)->replaceArray('?',$executed->bindings)->dump();
        });
        $user = User::first();
        dd($user->orderCompleted()->exists());
    }
}
