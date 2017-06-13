<?php
namespace Common\Model;
use Think\Model;

class MenuModel extends Model
{
    private $_db = "";
    public function __construct()
    {
        $this->_db = M('menu');
    }
    /**
     * 添加数据操作
     */
    public function insert($data = array())
    {
        if(!$data || !is_array($data)){
            return 0;
        }
        return $this->_db->add($data);
    }
    
    /**
     * 获取菜单分页列表
     */
    public function getMenus($data, $page, $pageSize)
    {
        $data['status'] = array('NEQ', -1);
        $offset = ($page - 1) * $pageSize;
        $list = $this->_db->where($data)->order('listorder asc,menu_id asc')->limit($offset, $pageSize)->select();
        return $list;
    }
    
    /**
     * 获取菜单模块数量
     */
    public function getMenusCount($data = array())
    {
        $data['status'] = array('neq', -1);
        return $this->_db->where($data)->count();
    }
    
    /**
     * 获取单个菜单模块信息
     */
    public function find($id)
    {
        if(!$id || !is_numeric($id)){
            return array();
        }
        return $this->_db->where('menu_id='.$id)->find();
    }
    
    /**
     * 更新模块信息
     */
    public function updateMenuById($id, $data)
    {
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        if(!$data || !is_array($data)){
            throw_exception('更新数据不合法');
        }
        return $this->_db->where('menu_id='.$id)->save($data);
    }
    
    /**
     * 更改模块状态信息
     */
    public function updateStatusById($id, $status)
    {
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        if(!$status || !is_numeric($status)){
            
            throw_exception('状态不合法');
        }
        
        $data['status'] =$status;
        return $this->_db->where("menu_id=".$id)->save($data);
    }
    
    /**
     * 更改模块排序值
     */
    public function updateMenulistorderById($id, $listorder)
    {
        
        if(!id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        
        $data['listorder'] = intval($listorder);
        return $this->_db->where("menu_id=".$id)->save($data);
    }
    
    /**
     * 获取后台菜单
     */
    public function getAdminMenus()
    {
        $data = array(
            'status' => array('neq', -1),
            'type' => 1,
            );
        return $this->_db->where($data)->order('listorder asc,menu_id asc')->select();
    }
    
    /**
     * 获取前端导航
     */
    public function getBarMenus()
    {
        $data = array(
            'status'=>array('neq',-1),
            'type' => 0
        );
        $res = $this->_db->where($data)
                ->order('listorder asc,menu_id asc')
                ->select();
        return $res;
    }
    
    public function getNormalBarMenus()
    {
        $data = array(
            'status'=>array('eq',1),
            'type' => 0
        );
        $res = $this->_db->where($data)
                ->order('listorder asc,menu_id asc')
                ->select();
        return $res;
    }
}