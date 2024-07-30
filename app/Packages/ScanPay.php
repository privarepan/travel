<?php
namespace App\Packages;
class ScanPay
{
    //网关地址
    public $gatewayUrl = "https://hmpay.sandpay.com.cn/gateway/api";
    //应用ID -- 代理商或商户ID
    public $appId = "661301000022162";
    //子应用ID -- 代理商下的商户ID 非代理商不填
    public $subAppId = "";
    //私钥值
    public $rsaPrivateKey = "MIIEowIBAAKCAQEAkBSOGBF3cYoo7y5RDw/y0+buSHG4/RDO7xUlGDyVNb5LL61mJGUeiwhyTKQT4pyNGw3kq2oFbRI2JJtyxWGf+/KTajIbuZIDkfKv1QEHslwnXM5AHH6wPS8JfMx9VXHipdk1GXlqUMYCaRMs52xsmKIX07Wyi7cGKWkvBUg4saKUj/aE352EU4GUSGsnteg/KhxNne81zPH0nlboHagt9P6vkoB30npYlvGU2wUZBjrsCP5tX3AejQcI98vBzpOL6wK1VQoAYFXSTaV1iqhhzNCkO5rw6NLR8JRonL55f1BiW/pUMfI2OXqT5xie24UNnH9nSgsBtbLP1cSmdjmtYwIDAQABAoIBAEdOlQCebGH6AcZWQvHUb9al/RpHhklg7zluWpyDJ0mg828WP24Vyab/uCnsLpdB6agRGTIo16Silb9KW+QKK/aymHi/ce9Gr5Ok65bc2qVQbH7G6P3xSWIOjICCaClIouZ3+IqyB4cQaJL+VYP3qktkNaymyUXSgC8win/vvXdT/jqF2Ibi5+HfCbRLmHmILQlNSoK9tRRqtrJkTSeAAMxdKX6yWycSvFmR5cf5z9cce/KdiH/fhaANrOXngBTu8erc5Spj1KjkoxmJqng0nWfw6Kd4L4Q8QbjhCcpT9VOxst98sZ4vduObs3+6LGPxv64F0iIT9xy/7w23+C9ibaECgYEA93e0CgVBV1RntYYyr3VApG3+JciV7rmV/+z2CTwviglzdkSIXQtzMPnZ/tyaoxKkXyLmyInF+CTN8LUR7B3eu4gf88PI9E8RW+9u56VtmAhBBElxuobk0tF+1pBiqueltEZTJlBjYgLTiMuZN+PXTw2+DZV7BNh8dPZ+f+vMenECgYEAlQxLM3Edgm3OCE+n0mbt4GpxA8V3hh8g/Q7uQZy5ef+/3owkJah55UXkO7G/0mQ68nginyoZxc3AoPUxVJf8cwxyQNPCEaBH7WWGCqwZ/zyNKFY74PjjLQ5Qa9Anr6/11HUcYpbsf8DHNJB+bjRFGRCsVrsGmxNhLq8CitirhxMCgYEAmuM+OYNeiUVYAAK1mKIfyorZplZsmVVpMBzlRZR5AMG3lc+BNhNjjsTjD6SN2QFjBfS3U87/rLeEemMqi4mKjf++V/kzvs36RHuRA8XD2YNZBlDdsOybLeRXqf4G84c3bKUTiAiKm56/PYCJLrUZXu5wBNqJe9fv1dkuBezVN9ECgYBqnNaVv02aZCzB6Shj6dgxdDHOvsrWFyIjoTBvoklRqMx3xcp9XVuD0lCFGonZVnSLx78MFrNEt/4XpAtbNTQQn1CCanYg4YNRnhMZQy19UrStq7E9JQpqyhhgZg5dLwIqrHJXWxlj8GMNiGXHMoBSg4iiqTj6aAxVtj2dT4qyHQKBgFl+6kwkY5J6xPjJDHdYBHeKTOEXAJH6Hjh+BesMajfv3ELbT3C8mK4F1Lo5qJ6CMhJMRS/t8T8QJyAabUc/oXCPco9D1Szq05h8uuoImS+mgSqrjA38sFclqocODr5V908wdDw40e0GznjL0vYVN1l4jaI3+Jn2yWRDzx4Tu6I3";
    //平台公钥
    public $platRsaPublicKey = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDFJ6Jx4Ogn85zfpVyHKVf/HWU5GgWBiYR+ThC5OtRJwBr0dld6sF2hRacJyCQfxBMgq89gvuXp96jYG4uyWUTpc0vCXp+t4Wjq+y2+3Ro4X3G4vY3PwYTUdVG38vcZZB/mPxD7sKP9YhSg2oN0M+lmDiXCokgKSbobhjJ2qyYhgQIDAQAB";
    //字符集编码
    public $charset = "UTF-8";
    //签名类型
    public $signType = "RSA";
    //数据格式
    public $format = "JSON";
    //api版本
    public $apiVersion = "1.0";


    public function test()
    {

        $url = $this->gatewayUrl;

        $appId = $this->appId;
        $subAppId = $this->subAppId;
        $method = "trade.percreate";
        $format = $this->format;
        $charset = $this->charset;
        $signType = $this->signType;
        $version = $this->apiVersion;
        $timestamp = date("Y-m-d h:i:s");
        $nonce = $this->getRandStr(8);
        $privateKey = $this->rsaPrivateKey;
        //支付方式
        $payWay = "AUTO";
        $createIp = "127.0.0.1";//生产环境使用真实ip
        $createTime = date("YmdHis");
        $expireTime = date("YmdHis", strtotime("+10 minute"));//过期时间
        $totalAmount = 1.00;
        $outOrderNo = $this->buildOrderNo();
        //商品详情 -- 按实际填写 或不填不上送
        $goodsDetail1 = [];
        $goodsDetail1['goods_id'] = 'a01';
        $goodsDetail1['goods_name'] = 'a1';
        $goodsDetail1['quantity'] = '1';
        $goodsDetail1['price'] = '0.01';

        $goodsDetail2 = array();
        $goodsDetail2['goods_id'] = 'a02';
        $goodsDetail2['goods_name'] = 'a2';
        $goodsDetail2['quantity'] = '1';
        $goodsDetail2['price'] = '0.99';
        $goodsDetail = array($goodsDetail1, $goodsDetail2);
        //订单详情
        $body = "无人超市";
        //商户交易门店
        $storeId = "100001";
        //终端信息
        $deviceInfo = array();
        $deviceInfo['longitude'] = '121.506377';
        $deviceInfo['latitude'] = '31.245105';

        $notifyUrl = null;

        $client = $this;
        $bizContent = $client->tradePrecreate($payWay, $createIp, $createTime, $expireTime, $totalAmount, $outOrderNo, $goodsDetail, $body, $storeId, $notifyUrl);
        $response = $client->execute($url, $client->request($appId, $subAppId, $method, $format, $charset, $signType, $timestamp, $nonce, $version, $bizContent, $privateKey));
        $jsonResp = json_decode($response, JSON_UNESCAPED_UNICODE);
        try {
            $client->checkResponseSign($jsonResp, $this->platRsaPublicKey, $signType);
        } catch (Exception $e) {
            error_log($e->getMessage());
            //todo 验签异常或失败
        }
    }

    public function notifyVerifyTest()
    {
        $str = "商户回调获取到GET请求参数";
        parse_str($str, $arr);
        //遍历打印
        var_export($arr);
        //sign_type参与签名调用V2
        $verifyB = $this->rsaCheckV2($arr, $this->platRsaPublicKey, $this->signType);
        echo "验签" . ($verifyB ? '通过' : '不通过');
    }

    public function execute($url, $request)
    {
        echo $request . "\n";
        $response = null;
        try {
            $response = $this->curl($url, $request);
        } catch (Exception $e) {
            error_log($e->getmessage());
        }
        echo $response . "\n";
        return $response;
    }

    public function request($appId, $subAppId, $method, $format, $charset, $signType, $timestamp, $nonce, $version, $bizContent, $privateKey)
    {
        $tradeRequest = array();
        $tradeRequest['app_id'] = $appId;
        $tradeRequest['sub_app_id'] = $subAppId;
        $tradeRequest['method'] = $method;
        $tradeRequest['format'] = $format;
        $tradeRequest['charset'] = $charset;
        $tradeRequest['sign_type'] = $signType;
        $tradeRequest['timestamp'] = $timestamp;
        $tradeRequest['nonce'] = $nonce;
        $tradeRequest['version'] = $version;
        $tradeRequest['biz_content'] = $bizContent;
        $tradeRequest['sign'] = $this->rsaSign($tradeRequest, $privateKey, $signType);
        $request = json_encode($tradeRequest, JSON_UNESCAPED_UNICODE);
        return $request;
    }

    public function tradePrecreate($payWay, $createIp, $createTime, $expireTime, $totalAmount, $outOrderNo, $goodsDetail, $body, $storeId, $notifyUrl)
    {
        $tradePrecreate = array();
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
        $tradePrecreateRequest = json_encode($tradePrecreate, JSON_UNESCAPED_UNICODE);
        return $tradePrecreateRequest;
    }

    /**
     * 验签
     * @param $respObject
     * @param $rsaPublicKey
     * @param string $signType
     * @throws Exception
     */
    public function checkResponseSign($respObject, $rsaPublicKey, $signType = 'RSA')
    {
        if (!$this->checkEmpty($rsaPublicKey) && !empty($respObject)) {
            //获取结果code
            $respCode = array_key_exists('code', $respObject) ? $respObject['code'] : null;
            $sign = array_key_exists('sign', $respObject) ? $respObject['sign'] : null;
            if ((!$this->checkEmpty($respCode) && $respCode === 200) || !$this->checkEmpty($sign)) {
                $checkResult = $this->rsaCheckV2($respObject, $rsaPublicKey, $signType);
                var_dump($checkResult);die;
                echo "验签" . ($checkResult ? '通过' : '不通过');
                if (!$checkResult) {
                    throw new Exception("check sign Fail!");
                }
            }
        }
    }

    public function rsaSign($params, $privateKey, $signType = "RSA")
    {
        return $this->sign($this->getSignContent($params), $privateKey, $signType);
    }

    public function getSignContent($params)
    {
        ksort($params);

        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                // 转换成目标字符集
                //$v = $this->doCharset($v, $this->charset);

                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }

        unset ($k, $v);
        return $stringToBeSigned;
    }

    protected function sign($data, $privateKey, $signType = "RSA")
    {
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($privateKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";

        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');

        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        } else {
            openssl_sign($data, $sign, $res);
        }

        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * RSA单独签名方法，未做字符串处理,字符串处理见getSignContent()
     * @param string $data 待签名字符串
     * @param string $privateKey 商户私钥
     * @param string $signType 签名方式，RSA:SHA1     RSA2:SHA256
     * @return string
     */
    public function aloneRsaSign($data, $privateKey, $signType = "RSA")
    {
        $priKey = $privateKey;
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($priKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";

        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');

        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        } else {
            openssl_sign($data, $sign, $res);
        }

        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * 校验$value是否非空
     * if not set ,return true;
     * if is null , return true;
     **/
    protected function checkEmpty($value)
    {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;

        return false;
    }

    /**
     * rsaCheckV2
     * 验证签名
     * sign_type参与签名
     **/
    public function rsaCheckV2($params, $rsaPublicKey, $signType = 'RSA')
    {
        $sign = $params['sign'];

        unset($params['sign']);
        return $this->verify($this->getCheckSignContent($params), $sign, $rsaPublicKey, $signType);
    }

    function getCheckSignContent($params)
    {
        ksort($params);

        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            // 转换成目标字符集
            // $v = $this->doCharset($v, $this->charset);

            if ($i == 0) {
                $stringToBeSigned .= "$k" . "=" . "$v";
            } else {
                $stringToBeSigned .= "&" . "$k" . "=" . "$v";
            }
            $i++;
        }

        unset ($k, $v);
        return $stringToBeSigned;
    }

    function verify($data, $sign, $rsaPublicKey, $signType = 'RSA')
    {
        $pubKey = $rsaPublicKey;
        $res = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($pubKey, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";

        ($res) or die('RSA公钥错误。请检查公钥文件格式是否正确');

        //调用openssl内置方法验签，返回bool值
        $result = FALSE;
        if ("RSA2" == $signType) {
            $result = (openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_SHA256) === 1);
        } else {
            $result = (openssl_verify($data, base64_decode($sign), $res) === 1);
        }
        return $result;
    }

    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    function doCharset($data, $targetCharset)
    {

        if (!empty($data)) {
            $fileType = $this->charset;
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
                //$data = iconv($fileType, $targetCharset.'//IGNORE', $data);
            }
        }
        return $data;
    }

    /**
     * 生成0-9a-ZA-Z随机数
     * @param int $length 输出长度
     * @return string
     */
    function getRandStr($length = 8)
    {
        // 随机字符集，可任意添加你需要的字符
        $chars = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            'a', 'b', 'c', 'd', 'e', 'f', 'g',
            'h', 'i', 'j', 'k', 'l', 'm', 'n',
            'o', 'p', 'q', 'r', 's', 't',
            'u', 'v', 'w', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F', 'G',
            'H', 'I', 'J', 'K', 'L', 'M', 'N',
            'O', 'P', 'Q', 'R', 'S', 'T',
            'U', 'V', 'W', 'X', 'Y', 'Z');
        // 在 $chars 中随机取 $length 个数组元素键名
        $keys = array_rand($chars, $length);
        $randStr = '';
        for ($i = 0; $i < $length; $i++) {
            // 将 $length 个数组元素连接成字符串
            $randStr .= $chars[$keys[$i]];
        }
        return $randStr;
    }

    /**
     * 利用php的uniqid函数 规定前缀为yyyyMM，返回值末尾带熵的值并替换小数点
     * 若是分布式服务，可以参考使用SnowFlake的PHP实现
     * @return string
     */
    private function buildOrderNo()
    {
        $uniqid = uniqid(date("Ym"), true);
        return str_replace(".", "", $uniqid);
    }

    /**
     * 获取服务器端IP地址
     * @return string
     */
    function getServerIp()
    {
        if (isset($_SERVER)) {
            if ($_SERVER['SERVER_ADDR']) {
                $server_ip = $_SERVER['SERVER_ADDR'];
            } else {
                $server_ip = $_SERVER['LOCAL_ADDR'];
            }
        } else {
            $server_ip = getenv('SERVER_ADDR');
        }
        return $server_ip;
    }

    /**
     * post 请求
     * @param $url
     * @param $data
     * @param int $timeout
     * @return string
     * @throws Exception
     */
    protected function curl($url, $data, $timeout = 30)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (substr($url, 0, 5) === "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        }
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $headers = array('content-type: application/json', 'Request-Trace-Id:' . $this->getRandStr(9));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $reponse = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception($reponse, $httpStatusCode);
            }
        }

        curl_close($ch);
        return $reponse;
    }
}
