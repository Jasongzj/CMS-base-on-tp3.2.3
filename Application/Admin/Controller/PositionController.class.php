<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Controller\CommonController;

class PositionController extends CommonController
{
    public function index()
    {
        $position = D('Position')->getPositions();
        $this->assign('position', $position);
        $this->display();
    }
    
    public function add()
    {
        if($_POST){
            if(!isset($_POST['name']) && !$_POST['name']){
                return show(0, "推荐位名称不能为空");
            }
            if(!isset($_POST['description']) && !$_POST['description']){
                return show(0, "推荐位描述不能为空");
            }
            
            //更新推荐位内容
            if($_POST['id']){
                return $this->save($_POST);
            }

            $positionId = D('Position')->insert($_POST);
            if(!$positionId){
                return show(0,'推荐位新增失败');
            }
            return show(1, '推荐位新增成功');
        } else {
            $this->display();
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
                $res = D('Position')->updateStatusById($id, $status);
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
    
    public function edit()
    {
        $positionId = $_GET['id'];
        if(!$positionId){
            $this->redirect('/admin.php?c=position');
        }
        $position = D('Position')->getOnePositionById($positionId);
        if(!$position){
            $this->redirect('/admin.php?c=position');
        }
        $this->assign('position',$position);
        $this->display();
        
    }
    
    private function save($data)
    {
        $id = $data['id'];
        unset($data['id']);
        try{
            $res = D('Position')->updateById($id, $data);
            if($res === false){
                return show(0, '操作失败');
            } else {
                return show(1, '操作成功');
            }
        } catch (Exception $e) {
            return show(0, $e->getMessage());
        }
    }
    
    
}