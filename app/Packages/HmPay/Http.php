<?php

namespace App\Packages\HmPay;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Http
{

    public function __construct(protected array $config)
    {
        $this->client = new Client();
    }

    public function post(array |Collection $body = [],array $options = [])
    {
        if ($body instanceof Collection) {
            $body = $body->toArray();
        }

        $response = new Response($this->client->post($this->config['base_uri'], array_merge([
            'json' => $body,
        ],$options + [
                'headers' => [
                    'Request-Trace-Id' => Str::random(9),
                ]
        ])));
        Log::channel('hmp')->debug($this->config['base_uri'],
            [
                'response' => $response->json(),
                'request' => $body,
                'config' => $this->config,
            ],
        );
        return $response;
    }



}
