<?php

namespace App\Packages\HmPay;

use Illuminate\Http\Client\Response as ClientResponse;
class Response extends ClientResponse
{
    public function isSuccess()
    {
        return $this->successful() && (int)$this->json('code') === 200;
    }
}
