<?php

namespace App\Packages\HmPay;


use Illuminate\Support\Str;

class HmPay
{
    //字符集编码
    public $charset = "UTF-8";
    //签名类型
    public $signType = "RSA";
    //数据格式
    public $format = "JSON";
    //api版本
    public $apiVersion = "1.0";

    protected $account;

    protected $http;
    public function __construct(protected array $config)
    {
        $this->http = new Http($this->config);
    }

    public function account(string $account = null)
    {
        if (!$account) {
            $this->getDefaultAccount();
            return $this;
        }
        $this->account = $this->config['accounts'][$account];
        return $this;
    }

    public function getDefaultAccount()
    {
        $accounts = $this->config['accounts'];
        return $this->account?:$accounts[$this->config['account']];
    }

    /**
     * todo 预下单
     * @return
     */
    public function tradePrecreate($body)
    {
        $post = $this->buildRequest('trade.percreate',$body);
        return $this->http->post($post);
    }

    protected function getSignContent($params)
    {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (filled($v) && "@" != substr($v, 0, 1)) {
                // 转换成目标字符集
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        return $stringToBeSigned;
    }

    protected function sign($params,$privateKey, $signType = "RSA")
    {
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($privateKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
        $data = $this->getSignContent($params);
        openssl_sign($data, $sign, $res);
        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        }

        return base64_encode($sign);
    }

    protected function bizContent($body)
    {
        return  json_encode($body,JSON_UNESCAPED_UNICODE);
    }

    protected function signMappings($account,$method)
    {
        return [
            'app_id' => $account['app_id'],
            'sub_app_id' => $account['sub_app_id'],
            'method' => $method,
            'format' => $this->format,
            'charset' => $this->charset,
            'sign_type' => $this->signType,
            'timestamp' => date("Y-m-d h:i:s"),
            'nonce' => Str::random(8),
            'version' => $this->apiVersion
        ];
    }

    public function buildRequest(string $method,array $body)
    {
        $account = $this->getDefaultAccount();
        $request = $this->signMappings($account, $method);
        $request['biz_content'] = $this->bizContent($body);
        $request['sign'] = $this->sign($request,$account['rsa_private_key'],$this->signType);
        return $request;
    }

    public function verifySign($data)
    {
        $post = collect($data);
        $sign = $post->pull('sign');
        $secret = $this->getSignContent($post->toArray());
        $account = $this->getDefaultAccount();
        $signType = $this->signType;
        $pubKey = $account['plat_rsa_public_key'];
        $res = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($pubKey, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";

        //调用openssl内置方法验签，返回bool值
        if ("RSA2" == $signType) {
            return openssl_verify($secret, base64_decode($sign), $res, OPENSSL_ALGO_SHA256) === 1;
        }
        return openssl_verify($secret, base64_decode($sign), $res) === 1;
    }
}
