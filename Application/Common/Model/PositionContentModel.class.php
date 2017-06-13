<?php
namespace Common\Model;
use Think\Model;

class PositionContentModel extends Model
{
    private $_db = '';
    
    public function __construct()
    {
        $this->_db = M('PositionContent');
    }
    
    public function getContent($data, $limit=0)
    {
        
        $data['status'] = array('neq', -1);
        if($data['title']){
            $data['title'] = array('like', '%'.$data['title'].'%');
        }
        $this->_db->where($data)->order('listorder asc,id asc');
        if($limit){
            $this->_db->limit($limit);
        }
        $list = $this->_db->select();
        return $list;
    }
    
    public function insert($data)
    {
        if(!is_array($data) || !$data){
            return 0;
        }
        $data['create_time'] = time();
        return $this->_db->add($data);
    }
    
    public function getOnePositionContent($id)
    {
        if(!is_numeric($id) && !$id){
            return 0;
        }
        return $this->_db->where("id=".$id)->find();
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
    public function updateListorderById($id, $listorder)
    {
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        $data = array('listorder' => intval($listorder));
        return $this->_db->where('id='.$id)->save($data);
    }
}