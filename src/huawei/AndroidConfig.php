<?php
/**
* Created by PhpStorm.
 * User: d00355631
* Date: 2019/11/6
* Time: 9:15
*/
namespace Alone\LaravelHuaweiPush\Huawei;

use Exception;

class AndroidConfig
{
    protected $collapse_key;
    protected $priority;
    protected $ttl;
    protected $bi_tag;
    protected $fast_app_target;
    protected $notification;
    protected $fields;

    public function __construct()
    {
        $this->priority = null;
        $this->notification = array();
        $this->fields = array();
    }

    public function getFields() {
        return $this->fields;
    }

    public function buildFields() {
        try{
            $this->check_parameter();
        }catch (Exception $e) {
            echo $e;
        }
        $keys = array(
            'collapse_key', 'priority', 'ttl', 'bi_tag', 'fast_app_target',
            'notification'
        );
        foreach ($keys as $key) {
            if (isset($this->$key)) {
                $this->fields[$key] = $this->$key;
            }
        }
    }

    public function collapse_key($value) {
        $this->collapse_key = $value;
    }
    public function priority($value) {
        $this->priority = $value;
    }
    public function ttl($value) {
        $this->ttl = $value;
    }
    public function bi_tag($value) {
        $this->bi_tag = $value;
    }
    public function fast_app_target($value) {
        $this->fast_app_target = $value;
    }
    public function notification($value) {
        $this->notification = $value;
    }

    private function check_parameter() {
        if (($this->collapse_key) && (gettype($this->collapse_key) != "integer")) {
            throw new Exception("type of collapse_key is wrong.");
        }
        if (($this->fast_app_target) && (gettype($this->fast_app_target) != "integer")) {
            throw new Exception("type of fast_app_target is wrong.");
        }
    }

}