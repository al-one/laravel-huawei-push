<?php
/**
 * Created by PhpStorm.
 * User: d00355631
 * Date: 2019/11/5
 * Time: 18:53
 */
namespace Alone\LaravelHuaweiPush\Huawei;

class Message
{
    //对应 测试用例中的push_admin字段，真正的message需要在app中外面继续封装一层才可以
    //todo 参数校验
    protected $data;
    protected $notification;
    protected $android;
    protected $token;
    protected $topic;
    protected $condition;
    protected $fields;

    public function __construct()
    {
        $this->fields = array();
        $this->token = array();
        $this->condition = null;
        $this->topic = null;
    }

    public function data($value) {
        $this->data = $value;
    }

    public function notification($value) {
        $this->notification = $value;
    }

    public function android($value) {
        $this->android = $value;
    }

    public function token($value) {
        $this->token = $value;
    }

    public function topic($value) {
        $this->topic = $value;
    }

    public function condition($value) {
        $this->condition = $value;
    }

    public function getFields() {
        return $this->fields;
    }

    public function buildFields() {
        $keys = array(
            'data', 'notification', 'android', 'token',
            'topic', 'condition'
        );
        foreach ($keys as $key) {
            if (isset($this->$key)) {
                $this->fields[$key] = $this->$key;
            }
        }
    }


}