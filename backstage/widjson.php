<?php
// 版权所有 晚江右海@字节元工作室群(王钰翔@青岛电子学校) 2023
function getMixinKey($orig) {
    $mixinKeyEncTab = array(
        46, 47, 18, 2, 53, 8, 23, 32, 15, 50, 10, 31, 58, 3, 45, 35, 27, 43, 5, 49, 33, 9, 42, 19, 29, 28, 14, 39,
        12, 38, 41, 13, 37, 48, 7, 16, 24, 55, 40, 61, 26, 17, 0, 1, 60, 51, 30, 4, 22, 25, 54, 21, 56, 59, 6, 63,
        57, 62, 11, 36, 20, 34, 44, 52
    );
    $mixinKey = "";
    foreach ($mixinKeyEncTab as $i) {
        $mixinKey .= $orig[$i];
    }
    return substr($mixinKey, 0, 32);
}
function encWbi($parameters, $imgKey, $subKey) {
    $mixinKey = getMixinKey($imgKey . $subKey);
    $currTime = time();
    $parameters["wts"] = $currTime;
    ksort($parameters);
    foreach ($parameters as $key => $value) {
        $parameters[$key] = str_replace(str_split("!'()*"), "", $value);
    }
    $query = http_build_query($parameters);
    $wbiSign = md5($query . $mixinKey);
    $parameters["w_rid"] = $wbiSign;
    return $parameters;
}
function getWbiKeys() {
    $response = file_get_contents("https://api.bilibili.com/x/web-interface/nav");
    $response = json_decode($response, true);
    $imgUrl = $response["data"]["wbi_img"]["img_url"];
    $imgUrl = explode("/", $imgUrl);
    $imgUrl = explode(".", end($imgUrl))[0];
    $subUrl = $response["data"]["wbi_img"]["sub_url"];
    $subUrl = explode("/", $subUrl);
    $subUrl = explode(".", end($subUrl))[0];
    return array($imgUrl, $subUrl);
}
list($imgKey, $subKey) = getWbiKeys();
$signedParams = encWbi(
    array(
        "foo" => "114",
        "bar" => "514",
        "baz" => "1919810"
    ),
    $imgKey,
    $subKey
);
$return = array(
    'code' => 0,
    'key' =>http_build_query($signedParams)
    );
echo json_encode($return);
//$query = http_build_query($signedParams);
//echo $query;
//echo $parameters["wts"];
?>