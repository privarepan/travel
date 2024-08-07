<?php

namespace App\Providers;

use App\Packages\HmPay\HmPay;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Ofcold\IdentityCard\IdentityCard;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('hmpay',function (){
            $config = config('hmp');
            return new HmPay($config);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->extendMobile();
        $this->extendIdCard();
    }

    protected function extendMobile()
    {
        Validator::extend('mobile', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^1[3-9]\d{9}$/', $value);
        });
    }

    public function extendIdCard()
    {
        Validator::extend('id_card', function ($attribute, $value, $parameters, $validator) {
            return IdentityCard::validate($value);
        });
    }
}
