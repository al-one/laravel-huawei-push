<?php

namespace Alone\LaravelHuaweiPush;

trait WithHuaweiNotification
{

    /**
     * 华为推送
     */
    public function toHuaweiPush($notifiable,$cfg = [])
    {
        $msg = new HuaweiMessage($this->title(),$this->content());
        if(isset($this->handler) && is_callable($this->handler))
        {
            $fun = $this->handler;
            $fun($msg,$notifiable,$cfg,'huawei');
        }
        return $msg;
    }

}
