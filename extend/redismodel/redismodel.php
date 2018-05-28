<?php
namespace redismodel;
use Redis;
/*
* @desc     基于Redis bitmap实现签到功能
*/
/**
 * 每当用户在某一天上线的时候,我们就使用SETBIT,以用户名作为key,
 * 将那天所代表的网站的上线日作为offset参数,并将这个offset上的为设置为1.
 * 比如,如果今天是网站上线的第100天,而用户$uid=10001在今天阅览过网站,
 * 那么执行命令SETBIT peter 100 1.
 * 如果明天$uid=10001也继续阅览网站,那么执行命令SETBIT peter 101 1 ,以此类推.
 * 当要计算$uid=10001总共以来的上线次数时,就使用BITCOUNT命令:
 * 执行BITCOUNT $uid=10001 ,得出的结果就是$uid=10001上线的总天数.
 * 签到后如果需要奖励判断可以另存key(uid:reward:day),里面可以存储对应的奖励及领奖标记位.
 */
class redismodel {
    const START_TIMESTRAMP = 1516686185; // 首日签到时间 20141221
    private $redis = NULL;
    const PREFIX = 'rank:article:';
    public function __construct($config) {
        $this->redis = new Redis();
        $this->redis->connect($config['host'], $config['port'], $config['timeout'], NULL);
    }
    public function getSignKey($uid) {
        return sprintf('member:sign:%d', $uid);
    }
    public function sign($uid, $now = NULL) {
        if ($now === NULL) {
            $now = time();
        }
        $offset = intval(($now - self::START_TIMESTRAMP) / 86400) + 1;
        $signKey = $this->getSignKey($uid);
        return $this->redis->setBit($signKey, $offset, 1);
    }
    public function getSign($uid, $now = NULL) {
        if ($now === NULL) {
            $now = time();
        }
        $offset = intval(($now - self::START_TIMESTRAMP) / 86400) + 1;
        $signKey = $this->getSignKey($uid);
        return $this->redis->getBit($signKey, $offset);
    }
    public function getSignCount($uid) {
        $signKey = $this->getSignKey($uid);
        return $this->redis->bitCount($signKey);
    }

    public function addScores($member, $scores) {
        $key = self::PREFIX;
        return $this->redis->zIncrBy($key, $scores, $member);
    }


    protected function getOneDayRankings($date, $start, $stop) {
        $key = self::PREFIX . $date;
        return $this->redis->zRevRange($key, $start, $stop, true);
    }


    protected function getMultiDaysRankings($dates, $outKey, $start, $stop) {
        $keys = array_map(function($date) {
            return self::PREFIX . $date;
        }, $dates);

        $weights = array_fill(0, count($keys), 1);
        $this->redis->zUnion($outKey, $keys, $weights);
        return $this->redis->zRevRange($outKey, $start, $stop, true);
    }


    public function getYesterdayTop10() {
        $date=date("Ymd",strtotime("-1 day"));
        return $this->getOneDayRankings($date, 0, 9);
    }

    /**设置阅读量的初始值
     * @param $key
     * @return bool
     */
    public function setValue($key){
        $key='article:num:'.$key;
        $data=$this->redis->set($key, 0);
        return $data;
    }

    /**设置阅读量每次加1
     * @param $key
     * @return int
     */
    public function incrValue($key){
        $key='article:num:'.$key;
        $data=$this->redis->incr($key);
        return $data;
    }

    /**获取文章的点击量
     * @param $key
     * @return bool|string
     */
    public function getValue($key){
        $key='article:num:'.$key;
        $data=$this->redis->get($key);
        return $data;
    }

    /**设置访问量加入队列
     * @param $strQueueName
     * @param $data
     * @return int
     */
    public function pushList($strQueueName,$data){
        $data=$this->redis->rPush($strQueueName,json_encode($data));
        return $data;
    }

    /**获取队列整体的长度
     * @param $strQueueName
     * @return array
     */
    public function getListRange($strQueueName){
        $data=$this->redis->lRange($strQueueName,0,-1);
        return $data;
    }

    /**让该队列依次出列
     * @param $strQueueName
     * @return string
     */
    public function lPopList($strQueueName){
        $data=$this->redis->lPop($strQueueName);
        return $data;
    }

}