<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;

class CronController
{
    public function dumpmysql()
    {
        $result = D('Basic')->select();
        if(!$result['dumpmysql']){
            die('系统未开启自动备份数据库');
        }
        $shell = "mysqldump -u".C("DB_USER")." -p".C("DB_PWD")." ".C("DB_NAME")." > /tmp/cms".date("Ymd")."sql";
        exec($shell);
        return show(1,'数据备份成功');
    }
}