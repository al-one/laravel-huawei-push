# Huawei Push For Laravel Notifications

## Installing

```
# composer.json

"minimum-stability": "dev",
"prefer-stable": true,
```

```sh
$ composer require "al-one/laravel-huawei-push" -vvv
```


## Config

```php
# optional if >= 5.5
# config/app.php
<?php

return [

    'providers' => [
        Alone\LaravelHuaweiPush\ServiceProvider::class,
    ],

];
```

```php
# config/services.php
[
    'huawei_push' => [
        'appid'   => '1234567890123456',
        'secret'  => 'abcdefghijklmn==',
        'bundles' => [ // 多包名
            'com.app.bundle_id' => [
               'appid'  => '1234567890123456',
               'secret' => 'abcdefghijklmn==',
           ],
        ],
    ],
];
```


## Usage

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model
{

    use Notifiable;

    /**
     * 推送路由
     */
    public function routeNotificationForHuaweiPush()
    {
        return $this->huawei_push_token;
    }
    
    /**
     * 如果不同用户所属的APP包名可能不同，请添加此方法
     */
    public function getAppPackage()
    {
        return 'com.app.bundle_id';
    }

}
```

```php
<?php
use Illuminate\Support\Facades\Notification;
use Alone\LaravelHuaweiPush\HuaweiNotification;
use Alone\LaravelHuaweiPush\HuaweiMessage;

$msg = (new HuaweiNotification)
    ->title('通知标题')
    ->body('通知内容')
    ->setHandler(function($msg,$notifiable,$cfg,$type = null)
    {
        if($msg instanceof HuaweiMessage)
        {
            $msg->ttl(86400);
            $msg->channel_id(8888);
        }
        return $msg;
    });

$user->notify($msg);
Notification::send($users,$msg);
```


## License

MIT