<?php
/**
 * Created by PhpStorm.
 * User: d00355631
 * Date: 2019/11/5
 * Time: 17:48
 */
namespace Alone\LaravelHuaweiPush\Huawei;

use Exception;
class AndroidNotification
{
    protected $title;
    protected $body;
    protected $icon;
    protected $color;
    protected $sound;
    protected $tag;
    protected $click_action;
    protected $body_loc_key;
    protected $body_loc_args;
    protected $title_loc_key;
    protected $title_loc_args;
    protected $channel_id;
    protected $notify_summary;
    protected $notify_icon;
    protected $style;
    protected $big_title;
    protected $big_body;
    protected $big_picture;
    protected $auto_clear;
    protected $notify_id;
    protected $group;
    protected $badge;
    protected $fields;

    public function __construct()
    {
        $this->click_action = array();
        $this->body_loc_args = array();
        $this->title_loc_args = array();
        $this->badge = array();
        $this->fields = array();
    }

    public function title($value) {
        $this->title = $value;
    }
    public function body($value) {
        $this->body = $value;
    }
    public function icon($value) {
        $this->icon = $value;
    }
    public function color($value) {
        $this->icon = $value;
    }
    public function sound($value) {
        $this->sound = $value;
    }
    public function tag($value) {
        $this->tag = $value;
    }
    public function click_action($value) {
        $this->click_action = $value;
    }
    public function body_loc_key($value) {
        $this->body_loc_key = $value;
    }
    public function body_loc_args($value) {
        $this->body_loc_args = $value;
    }
    public function title_loc_key($value) {
        $this->title_loc_key = $value;
    }
    public function title_loc_args($value) {
        $this->title_loc_args = $value;
    }
    public function channel_id($value) {
        $this->channel_id = $value;
    }
    public function notify_summary($value) {
        $this->notify_summary = $value;
    }
    public function notify_icon($value) {
        $this->notify_icon = $value;
    }
    public function style($value) {
        $this->style = $value;
    }
    public function big_title($value) {
        $this->big_title = $value;
    }
    public function big_body($value) {
        $this->big_body = $value;
    }
    public function big_picture($value) {
        $this->big_picture = $value;
    }
    public function auto_clear($value) {
        $this->auto_clear = $value;
    }
    public function notify_id($value) {
        $this->notify_id = $value;
    }
    public function group($value) {
        $this->group = $value;
    }
    public function badge($value) {
        $this->badge = $value;
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
            'title', 'body', 'icon', 'color', 'sound', 'tag', 'click_action', 'body_loc_key',
            'body_loc_args', 'title_loc_key', 'title_loc_args', 'channel_id', 'notify_summary',
            'notify_icon', 'style', 'big_title', 'big_body', 'big_picture', 'auto_clear', 'notify_id',
            'group', 'badge'
        );
        foreach ($keys as $key) {
            if (isset($this->$key)) {
                $this->fields[$key] = $this->$key;
            }
        }
    }

    private function check_parameter() {
        if (($this->style) && (gettype($this->style) != "integer")) {
            throw new Exception("type of style is wrong.");
        }
        if (($this->auto_clear) && (gettype($this->auto_clear) != "integer")) {
            throw new Exception("type of auto_clear is wrong.");
        }
        if (($this->notify_id) && (gettype($this->notify_id) != "integer")) {
            throw new Exception("type of notify_id is wrong.");
        }
    }

}