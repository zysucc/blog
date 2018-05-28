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
use app\index\model\Article as ArticleModel;
use app\index\model\Tip as TipModel;
use redismodel\redismodel;

class Index extends Base
{
    /**
     * 首页
     * @return mixed
     */
    public function index()
    {

        $ArticleModel = new ArticleModel();
        $indexdata['articles'] = $ArticleModel->getCateList(true, 0, 5);
        $TipModel = new TipModel();
        $tips = $TipModel::where('tip_view', 1)->select();
        $this->assign('tips', $tips);
        $this->assign('indexdata', $indexdata);
        $this->assign('title', "首页");
        return $this->fetch('index');
    }

    public function send_sign()
    {
        $config= config()['index_config']['redis_port'];
        $redismodel=new redismodel($config);
        $uid=session('qq.uid');
        $is_sign=$redismodel->getSign($uid,time());
        if($is_sign){
            return json(array('status'=>2,'message'=>'你已经签到了哦！~'));
            exit;
        }else{
            $redismodel->sign($uid,time());
            return json(array('status'=>1,'message'=>'签到成功！~'));
            exit;
        }
    }
}
