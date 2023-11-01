<?php
// 版权所有 晚江右海@字节元工作室群(王钰翔@青岛电子学校) 2023
//跨域
header('Content-Type: text/html;charset=utf-8');
header('Access-Control-Allow-Origin:*'); 
header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE'); 
header('Access-Control-Allow-Credentials: true'); 
header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin');
//传入uid
$uid = $_POST['uid'];
//设置官方账号cookie
$SESSDATA = "Your_Cookie_SESSDATA";
if (empty($uid)){
    $return = array(
        'code' =>100
        );
    echo json_encode($return);
    exit();
}
//连接数据库
$servername = "Your_database_address";
$username = "Your_database_username";
$password = "Your_database_password";
$dbname = "Your_database_name";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error!!!: " . $conn->connect_error);
}
//查询是否uid重用
// 声明数据库查询语句
$sql = "SELECT * FROM allowed_users WHERE uid='$uid'";
//查询开始 结果赋值到result
$result = $conn->query($sql);
//将result转为PHP数组以供调用
$row = $result->fetch_assoc();
if ($result->num_rows != 0){
    //查询是否存在结果
    $return = array(
        'code' =>500
    );
    echo json_encode($return);
    exit();
}
//查询是否存在开始
// 声明数据库查询语句
$sql = "SELECT * FROM Users WHERE uid='$uid' ORDER BY times DESC LIMIT 1";
//查询开始 结果赋值到result
$result = $conn->query($sql);
//将result转为PHP数组以供调用
$row = $result->fetch_assoc();
if ($result->num_rows == 0){
    //查询是否存在结果
    $return = array(
        'code' =>480
    );
    echo json_encode($return);
    exit();
}
//取数据库值用于以下代码的请求
$userkey = $row['key'];
$qq = $row['QQ'];
//获取Wbi 签名
$wid = file_get_contents("Your_Wbi_Server_Address/widjson.php");
$widkey = json_decode($wid, true);
$signkey = $widkey['key'];
//设置目标cookie
$cookie = "SESSDATA=$SESSDATA";
//设置私信API
$url = "https://api.vc.bilibili.com/svr_sync/v1/svr_sync/fetch_session_msgs?talker_id={$uid}&session_type=1";
// 初始化cURL
$ch = curl_init();
// 设置请求的URL和Cookie
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIE, $cookie);
// 发送请求并获取响应
$response = curl_exec($ch);
// 检查是否有错误发生
if (!$response) {
    exit();
} else {
//留空不执行
}
// 关闭cURL资源
curl_close($ch);
//判断用户是否发送了验证密钥
if(strpos($response,$userkey) == false){ 
    $return = array(
        'code' =>450
    );
    echo json_encode($return);
    exit();
   }
//设置查询互相关系的API、加上Wbi签名
$url = "https://api.bilibili.com/x/space/wbi/acc/relation?mid=$uid&".$signkey;
// 初始化cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIE, $cookie);
// 发送请求并获取响应
$response = curl_exec($ch);
// 检查是否有错误发生
if (!$response) {
    exit();
} else {
    //json处理
    $response = json_decode($response,true);
    $acode = $response['data']['be_relation']['attribute'];
    //检查关系是否为2(用户关注目标账号)或6(目标账号和用户互粉)
    if($acode==2||$acode==6){
        //写入已通过用户数据库
        $sql = "INSERT allowed_users(qq,uid) VALUES('$qq','$uid')";
        $conn->query($sql);
        //准备返回
        $return = array(
            'code'=> 0
        );
        //返回结果
        echo json_encode($return);
    }else{
        $return = array(
            'code' =>490
        );
        echo json_encode($return);
        exit();
    }   
}
?>
