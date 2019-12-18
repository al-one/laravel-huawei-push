<?php
/**
 * Created by PhpStorm.
 * User: d00355631
 * Date: 2019/11/5
 * Time: 18:57
 */
namespace Alone\LaravelHuaweiPush\Huawei;

class Notification
{
    // todo 参数校验
    protected $title;
    protected $body;
    protected $fields;

    public function __construct($title, $body) {
        $this->title = $title;
        $this->body = $body;
        $this->fields = array();
    }

    public function title($value) {
        $this->title = $value;
    }

    public function body($value) {
        $this->body = $value;
    }

    public function getFields() {
        return $this->fields;
    }

    public function buildFields() {
        $keys = array(
            'title', 'body'
        );
        foreach ($keys as $key) {
            if (isset($this->$key)) {
                $this->fields[$key] = $this->$key;
            }
        }
    }

}