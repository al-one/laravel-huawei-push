<?php

namespace Alone\LaravelHuaweiPush;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades;

class HuaweiPushChannel
{

    protected $config;

    public function __construct($cfg = 'huawei_push')
    {
        is_string($cfg) && $cfg = config("services.$cfg") ?: [];
        $this->config = (array)$cfg;
    }

    /**
     * 华为推送
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return mixed
     */
    public function send($notifiable,Notification $notification)
    {
        $pkg = null;
        if(is_object($notifiable) && method_exists($notifiable,'routeNotificationFor'))
        {
            if(!$sto = $notifiable->routeNotificationFor('huaweiPush'))
            {
                return false;
            }
            $pkg = $this->getAppPackage($notifiable);
        }
        else
        {
            $sto = $notifiable;
        }
        $cfg = $this->getConfig($pkg);
        if(!data_get($cfg,'secret'))
        {
            Facades\Log::warning("huawei push error: none config \t",compact('pkg','cfg','sto'));
            return false;
        }
        /** @var $notification Notification|HuaweiNotification */
        $msg = $notification->toHuaweiPush($notifiable,$cfg);
        $app = new HuaweiPushApplication(data_get($cfg,'appid'),data_get($cfg,'secret'));
        $sts = (array)($sto ?: []);
        $msg->token($sts); // 推送目标
        $ret = $app->push_send_msg($mdt = $msg->getFields());
        $eno = data_get($ret,'code');
        if($eno != '80000000')
        {
            $rts = [];
            if($eno == '80300007')
            {
                $rts = $sts;
                Facades\Log::notice("huawei push with illegal_token \t",compact('eno','ret','mdt'));
            }
            elseif($eno == '80100000')
            {
                $edt = data_get($ret,'msg');
                $edt = is_array($edt) ? $edt : (json_decode($edt,true) ?: []);
                $rts = data_get($edt,'illegal_tokens') ?: [];
                Facades\Log::notice("huawei push success with illegal_tokens \t",compact('eno','ret','mdt'));
            }
            else
            {
                Facades\Log::warning("huawei push error \t",compact('eno','ret','mdt'));
            }
            if($rts && method_exists($notifiable,'invalidNotificationRouters'))
            {
                $notifiable->invalidNotificationRouters($this,$rts,'token');
            }
        }
        else
        {
            Facades\Log::debug("huawei push success \t",compact('eno','ret','mdt'));
        }
        return $ret;
    }

    public function getAppPackage($notifiable)
    {
        if(method_exists($notifiable,'getAppPackage'))
        {
            $pkg = $notifiable->getAppPackage();
        }
        else
        {
            $pkg = data_get($notifiable,'app_package');
        }
        return $pkg;
    }

    public function getConfig($pkg = null,$dvc = null)
    {
        $cfg = $this->config ?: [];
        if(!empty($dvc) && isset($cfg[$dvc]))
        {
            $cfg = ($cfg[$dvc] ?: []) + $cfg;
        }
        if(!empty($pkg))
        {
            // 多包名不同配置
            if(isset($cfg['bundles'][$pkg]))
            {
                $cfg = ($cfg['bundles'][$pkg] ?: []) + $cfg;
            }
            elseif(isset($this->config['bundles'][$pkg]))
            {
                $cfg = ($this->config['bundles'][$pkg] ?: []) + $cfg;
            }
        }
        return Arr::except($cfg,['android','ios','bundles']);
    }

}
