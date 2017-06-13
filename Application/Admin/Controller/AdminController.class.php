<?php
namespace Admin\Controller;
use Think\Controller;

class AdminController extends CommonController
{
    public function index()
    {
        $adminlist = D('Admin')->getAdmins();
        $this->assign('adminlist', $adminlist);
        $this->display();
    }
    
    public function add()
    {
        if($_POST){
            if(!isset($_POST['username']) && !$_POST['username']){
                return show(0, '用户名不能为空');
            }
            if(!isset($_POST['password']) && !$_POST['username']){
                return show(0, '密码不能为空');
            }
            if(!isset($_POST['realname']) && !$_POST['realname']){
                return show(0, '真实姓名不能为空');
            }
            $_POST['password'] = getMD5password($_POST['password']);
            
            $admin = D("Admin")->getAdminByUsername($_POST['admin']);
            if($admin && $admin['status'] != -1){
                return show(0, '用户名已存在');
            }
            
            $id = D("Admin")->insert($_POST);
            if(!$id){
                return show(0, '新增失败');
            }
            return show(1, '新增成功');
        } else {
            $this->display();
        }
    }
    
    public function setStatus()
    {
        $data = array(
            'id' => intval($_POST['id']),
            'status' => intval($_POST['status']),
        );
        
        return parent::setStatus($data, 'Admin');
    }
    
    public function personal()
    {
        $res = $this->getLoginUser();
        $admin = D('Admin')->getAdminById($res['admin_id']);
        $this->assign('user', $admin);
        $this->display();
    }
    
    public function save()
    {
        $admin = $this->getLoginUser();
        if(!$admin){
            return show(0,'用户不存在');
        }
        
        $data['realname'] = $_POST['realname'];
        $data['email'] = $_POST['email'];
        
        try{
            $res = D("Admin")->updateAdminById($admin['admin_id'], $data);
            if(!$res){
                return show(0, '更新失败');
            }
            return show(1, '更新成功');
        } catch (Exception $e){
            return show(0, $e->getMessage());
        }
     }
}