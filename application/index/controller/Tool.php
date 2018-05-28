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
namespace app\index\controller;
use app\index\controller\Base;

class Tool extends Base
{
    /**
     * 工具箱首页
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 工具箱调度
     * @return mixed
     */
    public function info()
    {
        $view = request()->param("str");
        return $this->fetch($view);
    }

    public function best_index(){
        $options = [
            // 缓存类型为File
            'type'   => 'File',
            // 缓存有效期为永久有效
            'expire' => 0,
            // 指定缓存目录
            'path'   => APP_PATH . 'runtime/cache/',
        ];
        $value=0;
// 缓存初始化
// 不进行缓存初始化的话，默认使用配置文件中的缓存配置
        cache($options);

// 设置缓存数据
        cache('name', $value, 360000);
// 获取缓存数据
// 设置缓存的同时并且进行参数设置
        cache('test', $value, $options);
    } 

}