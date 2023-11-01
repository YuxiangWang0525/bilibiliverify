<?php
// 版权所有 晚江右海@字节元工作室群(王钰翔@青岛电子学校) 2023
//跨域
header('Content-Type: text/html;charset=utf-8');
header('Access-Control-Allow-Origin:*'); 
header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE'); 
header('Access-Control-Allow-Credentials: true'); 
header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin'); 
$QQ = $_POST['qq'];
$uid = $_POST['uid'];
if (empty($uid)||empty($QQ)){
    $return = array(
        'code' =>100
        );
    echo json_encode($return);
    exit();
}
//生成一个key
$key = "BMSG2022".substr(md5(time().md5($uid)."bmsgverify"),0,10);
//连接数据库
$servername = "Your_database_address";
$username = "Your_database_username";
$password = "Your_database_password";
$dbname = "Your_database_name";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error!!!: " . $conn->connect_error);
}
// 声明数据库数据插入SQL语句
$sql = "INSERT Users(`key`,qq,uid,times) VALUES('$key','$QQ','$uid',now())";
// 准备返回数据
$return = array(
    'code' =>0,
    'key' => $key
    );
//写入数据库
$conn->query($sql);
//将生成的key返回给客户端
echo json_encode($return);
?>