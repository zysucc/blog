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
use AMQPConnection;
use AMQPChannel;
use AMQPExchange;
use AMQPQueue;
class Rabbit extends Base
{
    /**
     * RabbitMq队列简单实现
     * @return mixed
     */
    public function index()
    {
        //RabbitMq配置文件
        $rabbit_config=config()['index_config']['rabbit_port'];
        //创建连接和channel
        $conn = new AMQPConnection($rabbit_config);
        $connect = $conn->connect();
        if (!$connect) {
            die("Cannot connect to the broker!\n");
        }
        $e_name = 'e_demo';
        $q_name = 'q_demo';
        $k_route = 'key_1';
        $channel = new AMQPChannel($conn);
        ##3，exchange 与  routingkey ： 交换机 与 路由键##
        //声明一个交换机
        $ex = new AMQPExchange($channel);
        //声明一个叫$e_name的交换机
        $ex->setName($e_name);
        //direct  声明路由类型
        $ex->setType(AMQP_EX_TYPE_DIRECT);
        //这个要创建的交换机是否持久化
        $ex->setFlags(AMQP_DURABLE);
        $status = $ex->declareExchange();  //声明一个新交换机，如果这个交换机已经存在了，就不需要再调用declareExchange()方法了.
        //声明一个队列
        $q = new AMQPQueue($channel);
        $q->setName($q_name);
        $status = $q->declareQueue(); //同理如果该队列已经存在不用再调用这个方法了。
        $msg='hello world';
        //给这个队列设置一个路由键
        $ex->publish($msg, $k_route);
    }

    public function test_cus(){
        //RabbitMq配置文件
        $rabbit_config=config()['index_config']['rabbit_port'];
        //创建连接和channel
        $conn = new AMQPConnection($rabbit_config);
        $e_name = 'e_demo';
        $q_name = 'q_demo';
        $k_route = 'key_1';
        if(!$conn->connect()){
            die('Cannot connect to the broker');
        }
        $channel = new AMQPChannel($conn);
        $ex = new AMQPExchange($channel);
        $ex->setName($e_name);
        $ex->setType(AMQP_EX_TYPE_DIRECT);
        $ex->setFlags(AMQP_DURABLE);
        $q = new AMQPQueue($channel);
        $q->setName($q_name);
        //把消息路由键与交换机绑定到一起
        $q->bind($e_name, $k_route);
        $arr = $q->get();
        dump($arr);
        $res = $q->ack($arr->getDeliveryTag());
        $msg = $arr->getBody();
        dump($msg);
    }


}