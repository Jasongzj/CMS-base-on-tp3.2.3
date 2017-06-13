<?php
namespace Common\Model;
use Think\Model;

class PositionModel extends Model
{
    private $_db = '';
    
    public function __construct()
    {
        $this->_db = M('Position');
    }
    
    public function insert($data)
    {
        if(!is_array($data) || !$data){
            return 0;
        }
        $data['create_time'] = time();
        return $this->_db->add($data);
    }
    
    public function updateById($id, $data)
    {
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        if(!$data || !is_array($data)){
            throw_exception('更新数据不合法');
        }
        
        return $this->_db->where('id='.$id)->save($data);
    }
    
    public function getOnePositionById($id)
    {
        return $this->_db->where("id=".$id)->find();
    }
    
    /**
     * 获取状态正常的推荐位
     */
    public function getNormalPositions()
    {
        $data = array('status' => 1);
        return $this->_db->where($data)->select();
    }
    
    /**
     * 获取未删除的推荐位
     */
    public function getPositions()
    {
        $data['status'] =array('neq', -1);
        return $this->_db->where($data)->select();
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
        return $this->_db->where("id=".$id)->save($data);
    }
    
    public function getPositionCount($data)
    {
        if(!$data['status']){
            $data['status'] = array('neq', -1);
        }
        
        return $this->_db->where($data)->count();
    }
}