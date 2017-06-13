<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController 
{
    public function index($type='')
    {
        //获取首页大图数据
        $topPicNews = D("PositionContent")->getContent(array('status' => 1,'position_id' => 2), 1);
        //首页3推荐位小图
        $topSmallNews = D("PositionContent")->getContent(array('status' => 1,'position_id' => 3), 3);
        //首页右侧广告位
        $advNews = D('PositionContent')->getContent(array('status' => 1, 'position_id' => 5),2);
        //首页文章摘要
        $listNews = D("News")->select(array('status' => 1,'thumb'=>array('neq', '')), 30);
        //文章排行
        $rankNews = $this->getRank();
        
        $this->assign('result', array(
            'topPicNews' => $topPicNews,
            'topSmallNews' => $topSmallNews,
            'listNews' => $listNews,
            'advNews' => $advNews,
            'rankNews' => $rankNews,
            'catid' => 0,
            ));
        /**
         * 生成静态化页面
         * 生成文件名，路径，模版
         */
        if($type == 'buildHtml'){
            $this->buildHtml('index', HTML_PATH, 'Index/index');
        } else {
            $this->display();
        }
    }
    
    /**
     * 首页静态化方法
     */
    public function build_html()
    {
        $this->index('buildHtml');
        return show(1,'首页缓存生成成功');
    }
    
    public function crontab_build_html()
    {
        if(APP_CRONTAB != 1){
            die("the_file_must_exec_crontab");
        }
        
        $res = D('Basic')->select();
        if(!$res['cacheindex']){
            die('系统没有设置自动生成首页缓存');
        }
        
        $this->index('buildHtml');
    }
    
    /**
     * 获取文章阅读量，返回结果至count.js
     */
    public function getCount()
    {
        if(!$_POST){
            return show(0, '没有任何内容');
        }
        
        $newsIds = array_unique($_POST);
        
        try{
            $list = D("News")->getNewsByIdIn($newsIds);
        } catch(Exception $e){
            return show(0, $e->getMessage());
        }
        
        if(!$list){
            return show(0, 'No Data');
        }
        
        $data = array();
        foreach($list as $k =>$v){
            $data[$v['news_id']] = $v['count'];
        }
        
        return show(1, 'success', $data);
    }
}