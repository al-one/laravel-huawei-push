<?php

namespace Alone\LaravelHuaweiPush;

use Illuminate\Support\Facades;

class HuaweiPushApplication extends Huawei\Application
{

    protected $cacheKey;

    public function __construct($appid,$appsecret)
    {
        parent::__construct($appid,$appsecret,Huawei\Constants::HW_TOKEN_SERVER,Huawei\Constants::HW_PUSH_SERVER);
        $this->cacheKey = "huawei-push-access-token-$appid-$appsecret";
        $tdt = Facades\Cache::get($this->cacheKey);
        if(!empty($tdt['token']))
        {
            $this->accesstoken       = data_get($tdt,'token');
            $this->token_expiredtime = data_get($tdt,'expire');
        }
    }

    public function refresh_token()
    {
        $ret = parent::refresh_token();
        if($ret)
        {
            Facades\Cache::put($this->cacheKey,[
                'token'  => $this->accesstoken,
                'expire' => $this->token_expiredtime,
            ],intval(($this->token_expiredtime - time() - 60) / 60));
        }
        else
        {
            Facades\Cache::forget($this->cacheKey);
        }
        return $ret;
    }

}
