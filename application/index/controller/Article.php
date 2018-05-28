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
use app\index\model\ArticleComment as ArticleCommentModel;
use Redis;
use redismodel\redismodel;

class Article extends Base
{
	/**
	 * ajax加载文章评论
	 * @return array
	 */
	public function ajaxList()
	{
		$request = request();
		if($request->isAjax()){
			$Article = new ArticleModel();
			$ArticleCommentModel = new ArticleCommentModel();
			$where['art_id'] = $request->param('id');
			$where['art_view'] = ['gt',0];
			$start = 5 + ($request->param('lenth')-1) * 5;
			$artinfo = $Article->where($where)->find();
			if(empty($artinfo)){
				return ["err"=>1,"msg"=>"对应的文章不存在","data"=>""];
			}
			$articlecommondata = $ArticleCommentModel->getOnelist($where['art_id'],$start,5);
			if(empty($articlecommondata)){
				return ["err"=>2,"msg"=>"没有啦!"];
			}
			return ["err"=>0,"data"=>getAjaxHtml($articlecommondata,2),"msg"=>"数据加载完成"];
		}else{
			return ["err"=>1,"msg"=>"错误请求方式"];
		}
	}

    /**
     * 文章详情
     * @return mixed
     */
    public function index()
    {
        $Article = new ArticleModel();
		$ArticleCommentModel = new ArticleCommentModel();
        $request = request();
        $where['art_id'] = $request->param('id');
        $where['art_view'] = ['gt',0];
        $articledata = $Article->getOne($where);
        if(empty($articledata)){
            abort(404,'文章不存在');
        }
        $art_id=$request->param('id');
        $config= config()['index_config']['redis_port'];
        $redismodel=new redismodel($config);
        $redismodel->incrValue($art_id);
        $articlecommondata = $ArticleCommentModel->getOnelist($where['art_id']);
        $articledata['art_hit']=$redismodel->getValue($art_id);
        $data=array(
            'art_id'=>$art_id,
            'art_hit'=>$articledata['art_hit']
        );
        $redismodel->pushList('article_list_push',$data);
        $other = $Article->getUpDown($where['art_id']);
        $this->assign('other',$other);
        $this->assign('articledata',$articledata);
        $this->assign('articlecommondata',$articlecommondata);
        $this->assign('title',$articledata['art_title']);
        return $this->fetch('index');
    }

}
