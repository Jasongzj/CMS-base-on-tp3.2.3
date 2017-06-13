<?php
namespace Admin\Controller;
use Think\Controller;

class ContentController extends CommonController
{
    public function index()
    {
        $data = array();
        //根据文章分类获取文章内容
        if(isset($_REQUEST['catid']) && $_REQUEST['catid']){
            $data['catid'] = intval($_REQUEST['catid']);
            $this->assign('catid', $data['catid']);
        } else {
            $this->assign('catid', -100);
        }
        
        //根据文章标题获取文章内容
        if(isset($_REQUEST['title']) && $_REQUEST['title']){
            $data['title'] = htmlspecialchars($_REQUEST['title']);
            $this->assign('title', $data['title']);
        }
        
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = $_REQUEST['pagesize'] ? $_REQUEST['pagesize'] : 5;
        $news = D('News')->getNews($data,$page,$pageSize);
        $newsCount = D('News')->getNewsCount($data);
        
        $res = new \Think\Page($newsCount, $pageSize);
        $pageRes = $res->show();
        $position = D('Position')->getNormalPositions();
        
        $this->assign('pageRes', $pageRes);
        $this->assign('news', $news);
        $this->assign('position', $position);
        $this->assign('webSiteMenu', D('Menu')->getBarMenus());
        $this->display();
    }
    
    public function add()
    {
        if($_POST){
            if(!isset($_POST['title']) || !$_POST['title']){
                return show(0, '标题不存在');
            }
            if(!isset($_POST['small_title']) || !$_POST['small_title']){
                return show(0, '短标题不存在');
            }
            if(!isset($_POST['catid']) || !$_POST['catid']){
                return show(0, '文章栏目不存在');
            }
            if(!isset($_POST['keywords']) || !$_POST['keywords']){
                return show(0, '关键字不存在');
            }
            if(!isset($_POST['content']) || !$_POST['content']){
                return show(0, '内容不存在');
            }
            
            if($_POST['news_id']){
                return $this->save($_POST);
            }
            
            $newsId = D('News')->insert($_POST);
            if($newsId){
                $newsContentData['content'] = $_POST['content'];
                $newsContentData['news_id'] = $newsId;
                $cId = D('NewsContent')->insert($newsContentData);
                if($cId){
                    return show(1,'新增成功');
                } else {
                    return show(1,'主表插入成功，副表插入失败');
                }
            } else {
                return show(0,'新增失败');
            }
            
        } else {
            
            $webSiteMenu = D('Menu')->getBarMenus();
            $titleFontColor = C("TITLE_FONT_COLOR");
            $copyFrom = C('COPY_FROM');
            $this->assign('webSiteMenu', $webSiteMenu);
            $this->assign('titleFontColor', $titleFontColor);
            $this->assign('copyFrom', $copyFrom);
            $this->display();
        }
    }
    
    public function edit()
    {
        $newsId = $_GET['id'];
        if(!$newsId){
            //执行跳转，跳转至文章列表
            $this->redirect('/admin.php?c=content');
        }
        //根据id获取文章内容
        $news = D('News')->getOneNews($newsId);
        if(!$news){
            $this->redirect('/admin.php?c=content');
        }
        //根据id获取文章的content
        $newsContent = D('NewsContent')->getOneNewsContent($newsId);
        if($newsContent){
            $news['content'] = $newsContent['content'];
        }
        //获取文章所属栏目
        $webSiteMenu = D('Menu')->getBarMenus();
        //将获取的数据赋值到模版中
        $this->assign('webSiteMenu', $webSiteMenu);
        $this->assign('titleFontColor', C('TITLE_FONT_COLOR'));
        $this->assign('copyFrom', C('COPY_FROM'));
        $this->assign('news', $news);
        return $this->display();
    }
    
    public function save($data)
    {
        $newsId = $data['news_id'];
        unset($data['news_id']);

        try{
            $id = D('News')->updateById($newsId, $data);
            $newsContentData['content'] = $data['content'];
            $contId = D('NewsContent')->updateNewsById($newsId, $newsContentData);
            if($id === false || $contId === false){
                return show(0, '更新失败');
            }
            return show(1, '更新成功');
        } catch(Exception $e){
            return show(0, $e->getMessage());
        }
    }
    
    
    public function setStatus()
    {
        try{
            if($_POST){
                $id = $_POST['id'];
                $status = $_POST['status'];
                if(!$id){
                    return show(0,'ID不存在');
                }
                $res = D('News')->updateStatusById($id, $status);
                if($res){
                    return show(1, '操作成功');
                } else {
                    return show(0, '操作失败');
                }
            }
        } catch(Exception $e){
            return show(0, $e->getMessage());
        }
    }
    
    public function listorder()
    {
        $listorder = $_POST['listorder'];
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $errors = array();
        if($listorder){
            try{
                foreach($listorder as $newsId => $val){
                    $id = D('News')->updateNewsListorderById($newsId, $val);
                    if($id === false){
                        $errors[] = $newsId;
                    }
                }
                if($errors){
                    return show(0,'排序失败-'.implode(',', $errors), array('jump_url' => $jumpUrl));
                }
                return show(1,'排序成功', array('jump_url' => $jumpUrl));
            } catch (Exception $e) {
            return show(0, $e->getMessage());
            }
        } 
        return show(0, '排序数据失败', array('jump_url' => $jumpUrl));
    }
    
    public function push()
    {
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $positionId = intval($_POST['position_id']);
        $newsId = $_POST['push'];
        
        try{
            if(!$newsId || !is_array($newsId)){
                return show(0, "请选择需要放至推荐位的文章");
            }
            if(!$positionId){
                return show(0, "没有选择推荐位");
            }
            $news = D('News')->getNewsByIdIn($newsId);
            if(!$news){
                return show(0,'没有相关内容');
            }
            foreach($news as $new){
                $data = array(
                    'position_id' => $positionId,
                    'title' => $new['title'],
                    'thumb' => $new['thumb'],
                    'news_id' => $new['news_id'],
                    'status' => 1,
                    'create_time' => $new['create_time'],
                );
                $position = D('PositionContent')->insert($data);
            }
        } catch(Exception $e){
            return show(0, $e->getMessage());
        }
        
        return show(1,'推荐成功', array('jump_url' => $jumpUrl));
    }
}