<?php namespace ProcessWire;
/**
 * Created by PhpStorm.
 * User: pruerowland
 * Date: 2019-02-20
 * Time: 10:56
 */

/**
 * @return array
 */
$info = array(
    'title' => __("PushAlert for ProcessWire"),
    'version' => "0.0.3",
    'summary' => __("This module enables you to send and manage push notifications from your ProcessWire 3.0.74+ website."),
    'author' => "Clip Magic",
    'href' => "https://www.clipmagic.com.au",
    'icon' => "bell",
    'requires' => array("PHP>=5.6.0", "ProcessWire>=3.0.74"),
    'installs' => array("FieldtypePushAlert"),
    'autoload' => true,
    'permission' => 'pushalert'
);


