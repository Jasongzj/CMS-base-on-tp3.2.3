<?php
namespace Common\Model;
use Think\Model;

class NewsModel extends Model
{
    private $_db = '';
    public function __construct()
    {
        $this->_db = M('news');
    }
    
    public function insert($data = array())
    {
        if(!is_array($data) || !$data){
            return 0;
        }
        $data['create_time'] = time();
        $data['username'] = getLoginUsername();
        return $this->_db->add($data);
    }
    
    public function select($data,$limit=0)
    {
        if($data['title']){
            $data['title'] = array('like', '%'.$data['title'].'%');
        }
        $this->_db->where($data)->order('listorder asc, news_id asc');
        if($limit){
            $this->_db->limit($limit);
        }
        $list = $this->_db->select();
        return $list;
    }
    
    public function getNews($data, $page, $pageSize)
    {
        $condition = $data;
        $condition['status'] = array('neq', -1);
        if(isset($data['title']) && $data['title']){
            $condition['title'] = array('like', '%'.$data['title'].'%');
        }
        if(isset($data['catid']) && $data['catid']){
            $condition['catid'] = intval($data['catid']);
        }
        $offset = ($page-1)*$pageSize;
        $list = $this->_db->where($condition)->order('listorder asc,catid asc')->limit($offset,$pageSize)->select();
        return $list;
    }
    
    public function getNewsCount($data)
    {
        $condition = $data;
        if(!$data['status']){
            $condition['status'] = array('neq', -1);
        }
        if(isset($data['title']) && $data['title']){
            $condition['title'] = array('like', '%'.$data['title'].'%');
        }
        if(isset($data['catid']) && $data['catid']){
            $condition['catid'] = intval($data['catid']);
        }
        return $this->_db->where($condition)->count();
    }
    
    public function getOneNews($id)
    {
        return $this->_db->where('news_id='.$id)->find();
    }
    
    public function updateById($id, $data)
    {
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        if(!$data || !is_array($data)){
            throw_exception('更新数据不合法');
        }
        
        return $this->_db->where('news_id='.$id)->save($data);
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
        return $this->_db->where("news_id=".$id)->save($data);
    }
    
    public function updateNewsListorderById($id, $listorder)
    {
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        $data = array('listorder' => intval($listorder));
        return $this->_db->where('news_id='.$id)->save($data);
    }
    
    /**
     * 根据newsIds获取一组新闻；
     */ 
    public function getNewsByIdIn($newsIds)
    {
        if(!is_array($newsIds)){
            throw_exception('参数不合法');
        }
        $data = array(
            'news_id' => array('in', implode(',', $newsIds)),
        );
        return $this->_db->where($data)->select();
    }
    
    public function getRank($data=array(), $limit=100)
    {
        $list = $this->_db->where($data)->order('count desc,news_id desc')->limit($limit)->select();
        return $list;
    }
    
    public function updateCount($id, $count)
    {
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        if(!is_numeric($count)){
            throw_exception("count不能为非数字");
        }
        
        $data['count'] = $count;
        return $this->_db->where("news_id=".$id)->save($data);
    }
    
    public function maxcount()
    {
        $data = array(
            'status' => 1,
        );
        return $this->_db->where($data)->order('count desc')->find();
        
    }
    
}