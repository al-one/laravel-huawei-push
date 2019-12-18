<?php
/**
 * Created by PhpStorm.
 * User: d00355631
 * Date: 2019/11/6
 * Time: 11:46
 */

namespace Alone\LaravelHuaweiPush\Huawei;


class Constants
{
    /**
     * 相关域名定义
     */
    const HW_TOKEN_SERVER = 'https://login.cloud.huawei.com/oauth2/v2/token';
    const HW_PUSH_SERVER= 'https://push-api.cloud.huawei.com/v1/{appid}/messages:send';



}