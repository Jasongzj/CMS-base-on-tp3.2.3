<?php
namespace Common\Model;
use Think\Model;

class AdminModel extends Model
{
    private $_db = '';
    public function __construct()
    {
        $this->_db = M('admin');
    }
    
    public function insert($data)
    {
        if(!$data || !is_array($data)){
            return 0;
        }
        return $this->_db->add($data);
    }
    
    public function getAdmins($data = array()){
        $data['status'] = array('neq', -1);
        return $this->_db->where($data)->select();
    }
    
    public function getAdminByUsername($username)
    {
        $result = $this->_db->where('username="'.$username.'"')->find();
        return $result;
    }
    
    public function getAdminById($id)
    {
        if(!$id || !is_numeric($id)){
            return 0;
        }
        return $this->_db->where('admin_id='.$id)->find();
    }
    
    public function updateAdminById($id, $data)
    {
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        if(!$data || !is_array($data)){
            throw_exception('更新数据不合法');
        }
        return $this->_db->where('admin_id='.$id)->save($data);
    }
    
    public function updateStatusById($id, $status)
    {
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        if(!is_numeric($status)){
            throw_exception('状态不能为非数字');
        }
        
        $data['status'] =$status;
        return $this->_db->where("admin_id=".$id)->save($data);
    }
    
    //获取今日登录用户数
    public function getLastLoginUsers()
    {
        $time = mktime(0,0,0,date('m'),date('d'),date('Y'));
        $data = array(
            'status' => 1,
            'lastlogintime' => array("gt", $time),
        );
        $res = $this->_db->where($data)->count();
        return $res['tp_count'];
    }
        
}