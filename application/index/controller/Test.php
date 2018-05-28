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
use Redis;
use app\index\model\Article as ArticleModel;
use redismodel\redismodel;
class Test extends Base
{
    /**
     * redis队列简单实现
     * @return mixed
     */
    public function index()
    {
        $redis=new Redis();
        $redis->connect('127.0.0.1', 6379);
        $strQueueName  = 'article_list_push';
        echo "---- 进队列成功 ---- <br /><br />";
        echo time().'<br/>';
        $re=$redis->lRange($strQueueName,0,-1);
        dump($re);
        $max=$redis->lLen($strQueueName);
        $count=0;
        while ($count <$max){
//          //出队列
            $result=$redis->lPop($strQueueName);
            $res=json_decode($result);
            $ArticleModel=new ArticleModel();
            $ArticleModel->upHotArticle($res->art_id,$res->art_hit);
            $count++;
        }
        //查看队列
        $strCount = $redis->lRange($strQueueName, 0, -1);
        echo "当前队列数据为： <br />";
        print_r($strCount);
        echo time().'<br/>';

    }

    public function test(){
        $Article = new ArticleModel();
        $where['art_id'] =13;
        $where['art_view'] = ['gt',0];
        $max=10000;
        $count=0;
        echo time().'<br />';
        while ($count <$max){
            $Article->getOneTest($where);
            $count++;
        }
        echo time();
        echo "<br /><br /> ---- 阅读量添加成功成功 ---- <br /><br />";
    }

    public function test1(){
        $art_id=13;
        $config= config()['index_config']['redis_port'];
        $redismodel=new redismodel($config);
        $max=10000;
        $count=0;
        echo time().'<br />';
        while ($count <$max){
            $redismodel->incrValue($art_id);
            $articledata['art_hit']=$redismodel->getValue($art_id);
            $data=array(
                'art_id'=>$art_id,
                'art_hit'=>$articledata['art_hit']
            );
            $redismodel->pushList('article_list_push',$data);
            $count++;
        }
        echo time();
        echo "<br /><br /> ---- 阅读量添加成功成功 ---- <br /><br />";

    }


}