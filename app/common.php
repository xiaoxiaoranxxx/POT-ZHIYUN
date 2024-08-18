<?php
// 应用公共文件
use IpLocation\IpLocation;


function gettime()
{
    return date('Y-m-d H:i:s');
}


function getipaddr()
{
    $ip = new IpLocation();
    $area = $ip->getlocation(get_client_ip());
    return iconv('gbk', 'utf-8', $area['country'] . $area['area']);
}

function getsipaddr($value)
{
    $ip = new IpLocation();
    $area = $ip->getlocation($value);
    return iconv('gbk', 'utf-8', $area['country'] . $area['area']);
}


function get_client_ip()
{
    // $type      = $type ? 1 : 0;
    // static $ip = null;
    // if (null !== $ip) {
    //     return $ip[$type];
    // }

    // if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    //     $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    //     $pos = array_search('unknown', $arr);
    //     if (false !== $pos) {
    //         unset($arr[$pos]);
    //     }

    //     $ip = trim($arr[0]);
    // } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
    //     $ip = $_SERVER['HTTP_CLIENT_IP'];
    // } elseif (isset($_SERVER['REMOTE_ADDR'])) {
       // $ip = $_SERVER['REMOTE_ADDR'];
    // }
    // IP地址合法验证
    // $long = sprintf("%u", ip2long($ip));
    // $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $_SERVER['REMOTE_ADDR'];
}

function decodeNTLMSSPType3($message)
{
    // 解码 base64 编码的消息
    $decodedMessage = base64_decode($message);

    // 解析字节流
    $flags = unpack('V', substr($decodedMessage, 20, 4))[1];

    // 提取所需字段
    $lmResponseOffset = 64; // LMResponse 字段偏移量
    $lmResponseLength = 24; // LMResponse 字段长度
    $lmResponse = substr($decodedMessage, $lmResponseOffset, $lmResponseLength);

    $ntlmResponseOffset = 88; // NTLMResponse 字段偏移量
    $ntlmResponseLength = 24; // NTLMResponse 字段长度
    $ntlmResponse = substr($decodedMessage, $ntlmResponseOffset, $ntlmResponseLength);

    $domainLength = unpack('v', substr($decodedMessage, 12, 2))[1];
    $domainOffset = unpack('V', substr($decodedMessage, 16, 4))[1];
    $domain = substr($decodedMessage, $domainOffset, $domainLength);

    $userLength = unpack('v', substr($decodedMessage, 28, 2))[1];
    $userOffset = unpack('V', substr($decodedMessage, 24, 4))[1];
    $user = substr($decodedMessage, $userOffset, $userLength);

    $hostLength = unpack('v', substr($decodedMessage, 44, 2))[1];
    $hostOffset = unpack('V', substr($decodedMessage, 48, 4))[1];
    $host = substr($decodedMessage, $hostOffset, $hostLength);

    // 返回解码后的字段
    return array(
        'flags' => $flags,
        'domain' => $domain,
        'user' => $user,
        'host' => $host,
        'lmResponse' => bin2hex($lmResponse),
        'ntlmResponse' => bin2hex($ntlmResponse)
    );
}
