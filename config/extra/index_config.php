<?php
// +----------------------------------------------------------------------
// | 青春博客 thinkphp5 版本
// +----------------------------------------------------------------------
// | Copyright (c) 2013~2016 http://loveteemo.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: long <admin@loveteemo.com>
// +----------------------------------------------------------------------
return [

    // +----------------------------------------------------------------------
    // | 常用配置 这里的对其方式需要改成 tab
    // +----------------------------------------------------------------------
    'redis_port' => [
        'host'    => '127.0.0.1',
        'port'    => '6379',
        'timeout' => '1'
    ],

    'rabbit_port' => [
        'host'     => '127.0.0.1',
        'port'     => '5672',
        'login'    => 'zhaoyu',
        'password' => '643925',
        'vhost'    => '/'
    ],

];