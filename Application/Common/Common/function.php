<?php

/**
 * 向前端返回一个json格式的状态信息
 */
function show($status, $message, $data=array()) 
{
    $result = array(
        'status' => $status,
        'message'=> $message,
        'data'   => $data
    );
    exit (json_encode($result));    
}

/**
 * 将密码加上一个前缀后进行md5加密并返回
 */
function getMd5Password($password)
{
    return md5($password . C('MD5_PRE'));
}

/**
 * 获取菜单管理中的菜单类型
 */
function getMenuType($type)
{
    return $type == 1 ? "后台菜单" : "前端导航";
}

/**
 * 获取菜单管理中的状态名称
 */
function getStatus($status)
{
    if($status == 0){
        $str = '关闭';
    } elseif($status == 1){
        $str = '正常';
    } elseif($status == -1){
        $str = '删除';
    }
    return $str;
}

function getAdminMenuUrl($nav)
{
    $url = '/admin.php?c='.$nav['c'].'$a='.$nav['f'];
    if($nav['f']=='index'){
        $url = '/admin.php?c='.$nav['c'];
    }
    return $url;
}

function getActive($navc)
{
    $c = strtolower(CONTROLLER_NAME);
    if(strtolower($navc) == $c){
        return 'class="active"';
    }
    return '';
}

function showKind($status, $data)
{
    header('Content-type:application/json;charset=UTF-8');
    if($status==0){
        exit(json_encode(array('error'=>0,'url'=>$data)));
    }
    exit(json_encode(array('error'=>1,'message'=>'上传失败')));
}

function getLoginUsername()
{
    return I('session.adminUser') ? I('session.adminUser') : 0;
}

function getCatName($navs, $id)
{
    foreach($navs as $nav){
        $navList[$nav['menu_id']] = $nav['name'];
    }
    return isset($navList[$id])? $navList[$id] : "";
}

function getCfromName($id)
{
    $copyfroms = C("COPY_FROM");
    return isset($copyfroms[$id]) ? $copyfroms[$id] : '';
}

function isThumb($thumb)
{
    if($thumb){
        return '<span style="color:red">有</span>';
    }
    return '无';
}

function getPositionName($positions,$id)
{
    foreach($positions as $position){
        $positionlist[$position['id']] = $position['name'];
    }
    return isset($positionlist[$id]) ? $positionlist[$id] : '';
}