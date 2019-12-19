<?php

namespace Alone\LaravelHuaweiPush;

/**
 * @mixin Huawei\Message
 * @mixin Huawei\Notification
 * @mixin Huawei\AndroidNotification
 * @mixin Huawei\AndroidConfig
 * @mixin Huawei\ClickAction
 * @method void clickType($value)
 */
class HuaweiMessage
{

    /**
     * @link https://developer.huawei.com/consumer/cn/service/hms/catalog/huaweipush_v3.html?page=hmssdk_huaweipush_api_reference_messagesend#Message
     */
    public $message;

    /**
     * https://developer.huawei.com/consumer/cn/service/hms/catalog/huaweipush_v3.html?page=hmssdk_huaweipush_api_reference_messagesend#Notification
     */
    public $notification;

    /**
     * @link https://developer.huawei.com/consumer/cn/service/hms/catalog/huaweipush_v3.html?page=hmssdk_huaweipush_api_reference_messagesend#AndroidNotification
     */
    public $androidNotification;

    /**
     * @link https://developer.huawei.com/consumer/cn/service/hms/catalog/huaweipush_v3.html?page=hmssdk_huaweipush_api_reference_messagesend#AndroidConfig
     */
    public $androidConfig;

    /**
     * @link https://developer.huawei.com/consumer/cn/service/hms/catalog/huaweipush_v3.html?page=hmssdk_huaweipush_api_reference_messagesend#ClickAction
     */
    public $clickAction;

    public function __construct($title = null,$body = null)
    {
        $this->clickAction = new Huawei\ClickAction();
        $this->clickAction->type(3);

        $this->androidNotification = new Huawei\AndroidNotification();
        $this->androidNotification->title($title);
        $this->androidNotification->body($body);
        //$this->androidNotification->notify_summary('');
        //$this->androidNotification->sound('');
        //$this->androidNotification->channel_id('');
        $this->androidNotification->style(0);
        $this->androidNotification->notify_id(rand(1,5));
        $this->androidNotification->auto_clear(86400);

        $this->androidConfig = new Huawei\AndroidConfig();
        $this->androidConfig->collapse_key(0);
        $this->androidConfig->ttl('86400s');
        $this->androidConfig->bi_tag('callback');

        $this->notification = new Huawei\Notification($title,$body);
        $this->message = new Huawei\Message();
    }

    /**
     * 推送目标
     */
    public function token($token)
    {
        $this->message->token((array)$token);
        return $this;
    }

    public function getFields()
    {
        $this->buildFields();
        return $this->message->getFields();
    }

    public function buildFields()
    {
        $this->clickAction->buildFields();
        $this->androidNotification->click_action($this->clickAction->getFields());
        $this->androidNotification->buildFields();
        $this->androidConfig->notification($this->androidNotification->getFields());
        $this->androidConfig->buildFields();
        $this->notification->buildFields();
        $this->message->notification($this->notification->getFields());
        $this->message->android($this->androidConfig->getFields());
        $this->message->buildFields();
        return $this;
    }

    public function __call($fun,$args)
    {
        $has = false;
        if(!in_array($fun,['notification','click_action']))
        {
            if(method_exists($this->clickAction,$fun) || in_array($fun,['clickType']))
            {
                $has = true;
                $f = $fun == 'clickType' ? 'type' : $fun;
                $this->clickAction->$f(...$args);
            }
            if(method_exists($this->androidNotification,$fun))
            {
                $has = true;
                $this->androidNotification->$fun(...$args);
            }
            if(method_exists($this->androidConfig,$fun))
            {
                $has = true;
                $this->androidConfig->$fun(...$args);
            }
            if(method_exists($this->notification,$fun))
            {
                $has = true;
                $this->notification->$fun(...$args);
            }
        }
        if($has)
        {
            return $this;
        }
        else
        {
            throw new \RuntimeException("method $fun not exists.");
        }
    }

}