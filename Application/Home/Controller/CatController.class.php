<?php
namespace Home\Controller;
use Think\Controller;

class CatController extends CommonController
{
    public function index()
    {
        $id = intval($_GET['id']);
        if(!$id) {
            return $this->error('ID不存在');
        }
        $nav = D("Menu")->find($id);
        if(!$nav || $nav['status'] != 1){
            return $this->error('栏目ID不存在或状态不为正常');
        }
        
        //分页形式获取文章信息
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = 20;
        $data = array(
            'status' => 1,
            'thumb' => array('neq', ''),
            'catid' => $id,
        );
        $news = D('News')->getNews($data,$page,$pageSize);
        $newsCount = D('News')->getNewsCount($data);
        
        $res = new \Think\Page($newsCount, $pageSize);
        $pageRes = $res->show();
        
        //首页右侧广告位
        $advNews = D('PositionContent')->getContent(array('status' => 1, 'position_id' => 5),2);
        //文章排行
        $rankNews = $this->getRank();
        
        $this->assign('result', array(
            'listNews' => $news,
            'advNews' => $advNews,
            'rankNews' => $rankNews,
            'catid' => $id,
            'pageres' => $pageRes,
            ));
        
        $this->display();
    }
}