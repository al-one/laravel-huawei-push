<?php
/**
 * Created by PhpStorm.
 * User: d00355631
 * Date: 2019/11/5
 * Time: 19:00
 */
namespace Alone\LaravelHuaweiPush\Huawei;

use Exception;
class ClickAction
{
    protected $type;
    protected $intent;
    protected $url;
    protected $rich_resource;
    protected $fields;

    public function __construct()
    {
        $this->rich_resource = null;
        $this->url = null;
    }

    public function type($value) {
        $this->type = $value;
    }
    public function intent($value) {
        $this->intent = $value;
    }
    public function url($value) {
        $this->url = $value;
    }
    public function rich_resource($value) {
        $this->rich_resource = $value;
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
            'type', 'intent', 'url', 'rich_resource'
        );
        foreach ($keys as $key) {
            if (isset($this->$key)) {
                $this->fields[$key] = $this->$key;
            }
        }
    }

    private function check_parameter() {
        if (($this->type) && (gettype($this->type) != "integer")) {
            throw new Exception("type of type is wrong.");
        }
    }
}