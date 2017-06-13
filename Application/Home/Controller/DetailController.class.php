<?php
namespace Home\Controller;
use Think\Controller;

class DetailController extends CommonController
{
    public function index()
    {
        $id = intval($_GET['id']);
        if(!$id || $id<0){
            return $this->error("ID不合法");
        }
        
        $news = D("News")->getOneNews($id);
        
        if(!$news || $news['status'] != 1){
            return $this->error("资讯不存在或已被关闭");
        }
        
        //增加一次阅读数
        $count = intval($news['count'] + 1);
        D("News")->updateCount($id, $count);
        
        $content = D("NewsContent")->getOneNewsContent($id);
        $news['content'] = htmlspecialchars_decode($content['content']);
        
        //首页右侧广告位
        $advNews = D('PositionContent')->getContent(array('status' => 1, 'position_id' => 5),2);
        //文章排行
        $rankNews = $this->getRank();
        
        $this->assign('result', array(
            'rankNews' => $rankNews,
            'advNews' => $advNews,
            'catid' => $news['catId'],
            'news' => $news,
        ));
        $this->display('Detail/index');
    }
    
    public function view()
    {
        $admin = getLoginUsername();
        if(!getLoginUsername()){
            $this->error('您没有权限访问该页面');
        }
        $this->index();
    }
}