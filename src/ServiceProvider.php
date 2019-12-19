<?php

namespace Alone\LaravelHuaweiPush;

use Illuminate\Notifications;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class ServiceProvider extends BaseServiceProvider
{

    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        /**
         * 通知驱动
         */
        $this->app->extend(Notifications\ChannelManager::class,function(Notifications\ChannelManager $channel)
        {

            /**
             * 华为推送
             */
            $channel->extend('huawei_push',function(Application $app)
            {
                return new HuaweiPushChannel('huawei_push');
            });

            return $channel;
        });

    }

}