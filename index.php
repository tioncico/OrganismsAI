<?php
/**
 * Created by PhpStorm.
 * User: tioncico
 * Date: 18-10-14
 * Time: 下午9:47
 */

chmod('1.txt',0777);

include "./vendor/autoload.php";
include 'Conf/config.php';
$a = new \App\Controller\Index();
$a->action();

//$arr = range(0,99);
//var_dump($arr);