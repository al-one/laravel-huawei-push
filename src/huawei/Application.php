<?php
/**
 * Created by PhpStorm.
 * User: d00355631
 * Date: 2019/11/5
 * Time: 19:17
 */
namespace Alone\LaravelHuaweiPush\Huawei;

class Application
{
    protected $appid;
    protected $appsecret;
    protected $token_expiredtime;
    protected $accesstoken;
    protected $validate_only;
    protected $hw_token_server;
    protected $hw_push_server;


    public function __construct($appid, $appsecret, $hw_token_server, $hw_push_server)
    {
        $this->appid = $appid;
        $this->appsecret = $appsecret;
        $this->token_expiredtime = null;
        $this->accesstoken = null;
        $this->hw_token_server = $hw_token_server;
        $this->hw_push_server = $hw_push_server;
        $this->validate_only = false;
    }

    public function appid($value) {
        $this->appid = $value;
    }
    public function appsecret($value) {
        $this->appsecret = $value;
    }
    public function validate_only($value) {
        $this->validate_only = $value;
    }

    public function is_token_expired() {
        if (empty($this->accesstoken)) {
            return true;
        }
        if (time()>$this->token_expiredtime) {
            return true;
        }
        return false;
    }

    public function curl_https_post($url, $data=array(), $header=array()) {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        //解决这个问题SSL: no alternative certificate subject name matches target host name
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Post提交的数据包

        $ret = @curl_exec($ch);
        if ($ret === false) {
            return null;
        }

        curl_close($ch);
        return $ret;
    }

    public function refresh_token() {
        $result = json_decode($this->curl_https_post($this->hw_token_server, http_build_query(
            array( "grant_type" => "client_credentials",
                   "client_secret" => $this->appsecret,
                   "client_id" => $this->appid, )),
            array("Content-Type: application/x-www-form-urlencoded;charset=utf-8")));

        $this->accesstoken = $result->access_token;
        $this->token_expiredtime = time() + $result->expires_in;
        return $result->access_token;
    }


    public function push_send_msg($msg) {
        $body = array(
            "validate_only" => $this->validate_only,
            "message" => $msg
        );

        if ($this->is_token_expired()) {
            $this->refresh_token();
        }

        $result = json_decode($this->curl_https_post(
            str_replace('{appid}', $this->appid, $this->hw_push_server),
            json_encode($body),
            array("Content-Type: application/json",
                "Authorization: Bearer {$this->accesstoken}")   // Use bearer auth
        ));

        //eg: {"code":"80000000","msg":"Success","requestId":"157278422841836431010901"}
        return $result;
}

}
