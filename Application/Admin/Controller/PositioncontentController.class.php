<?php
namespace Admin\Controller;
use Think\Controller;

class PositioncontentController extends CommonController
{
    public function index()
    {
        $positions = D('Position')->getNormalPositions();
        
        if($_GET['title']){
            $data['title'] = trim($_GET['title']);
            $this->assign('title', $data['title']);
        }
        if(isset($_GET['position_id']) && $_GET['position_id']){
            $data['position_id'] = intval($_GET['position_id']);
        }
        $contents = D('PositionContent')->getContent($data);
        $this->assign('positions', $positions);
        $this->assign('contents', $contents);
        $this->assign('positionId', $data['position_id']);
        $this->display();
    }
    
    public function add()
    {
        if($_POST){
            if(!isset($_POST['title']) && !$_POST['title']){
                return show(0, '标题不能为空');
            }
            if(!$_POST['url'] && !$_POST['news_id']){
                return show(0, 'url和文章Id不能同时为空');
            }
            if(!isset($_POST['thumb']) || !$_POST['thumb']){
                if($_POST['news_id']){
                    $res = D("News")->getOneNews($_POST['news_id']);
                    if($res && is_array($res)){
                        $_POST['thumb'] = $res['thumb'];
                    } else {
                        show(0, "输入的文章Id不存在");
                    }
                } else {
                    return show(0, '图片不能为空');
                }
            }
            
            if($_POST['id']){
                return $this->save($_POST);
            }
            
            try {
                $id = D("PositionContent")->insert($_POST);
                if(!$id){
                    return show(0, '新建推荐内容失败');
                } else {
                    return show(1, '新建推荐内容成功');
                }
            } catch(Exception $e){
                return show(0, $e->getMessage());
            }
        }
        $positions = D('Position')->getNormalPositions();
        $this->assign('positions', $positions);
        $this->display();
    }
    
    public function edit()
    {
        $id = $_GET['id'];
        $pcontent = D('PositionContent')->getOnePositionContent($id);
        $positions = D('Position')->getNormalPositions();
        $this->assign('positions', $positions);
        $this->assign('pcontent', $pcontent);
        $this->display();
    }
    
    private function save($data)
    {
        $id = $data['id'];
        unset($data['id']);
        
        try{
            
            $res = D("PositionContent")->updateById($id, $data);
            if($res === false){
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
                    return show(0, 'ID不存在');
                }
                $res = D('PositionContent')->updateStatusById($id, $status);
                if(!$res){
                    return show(0, "操作失败");
                } else {
                    return show(1, "操作成功");
                }
            }
        } catch (Exception $e){
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
                foreach($listorder as $id => $val){
                    $id = D('PositionContent')->updateListorderById($id, $val);
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
}