<?php

function request($url, $data = null, $headers = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    if($data):
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    endif;
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if($headers):
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 1);
    endif;

    curl_setopt($ch, CURLOPT_ENCODING, "GZIP");
    return curl_exec($ch);
}
function getstr($str, $exp1, $exp2)
{
    $a = explode($exp1, $str)[1];
    return explode($exp2, $a)[0];
}

function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}
include "config.php";
$guid1 = gen_uuid();
$guid2 = gen_uuid();

echo "Nomor Gopay : ";
$nohp = trim(fgets(STDIN));
$url = "https://goid.gojekapi.com/goid/login/request";
$headers = array();
$headers[] = "X-Appversion: 4.46.2";
$headers[] = "X-Appid: com.gojek.app";
$headers[] = "X-Session-Id: $guid1";
$headers[] = "X-Platform: iOS";
$headers[] = "X-Uniqueid: $guid2";
$headers[] = "Accept: application/json";
$headers[] = "X-User-Type: customer";
$headers[] = "X-Deviceos: iOS, 15.5";
$headers[] = "X-Phonemake: Apple";
$headers[] = "X-Phonemodel: Apple, iPhone 13 Pro";
$headers[] = "Content-Type: application/json; charset=UTF-8";
$headers[] = "Accept-Encoding: gzip, deflate";
$headers[] = "User-Agent: okhttp/3.12.13";
$data = '{"client_id":"gojek:consumer:app","client_secret":"pGwQ7oi8bKqqwvid09UrjqpkMEHklb","country_code":"+62","login_type":"","magic_link_ref":"","phone_number":"'.$nohp.'"}';
$requestLogin = request($url, $data, $headers);
if(strpos($requestLogin, '"success":true')!==false)
{
    $otpToken = getstr($requestLogin, 'otp_token":"','"');
}
else
{
    echo "Login error\n";
    echo "$requestLogin\n";
}

echo "OTP : ";
$otp = trim(fgets(STDIN));
$url = "https://goid.gojekapi.com/goid/token";
$headers = array();
$headers[] = "X-Appversion: 4.46.2";
$headers[] = "X-Appid: com.gojek.app";
$headers[] = "X-Session-Id: $guid1";
$headers[] = "X-Platform: iOS";
$headers[] = "X-Uniqueid: $guid2";
$headers[] = "Accept: application/json";
$headers[] = "X-User-Type: customer";
$headers[] = "X-Deviceos: iOS, 15.5";
$headers[] = "X-Phonemake: Apple";
$headers[] = "X-Phonemodel: Apple, iPhone 13 Pro";
$headers[] = "Content-Type: application/json; charset=UTF-8";
$headers[] = "Accept-Encoding: gzip, deflate";
$headers[] = "User-Agent: okhttp/3.12.13";
$data = '{"client_id":"gojek:consumer:app","client_secret":"pGwQ7oi8bKqqwvid09UrjqpkMEHklb","data":{"otp":"'.$otp.'","otp_token":"'.$otpToken.'"},"grant_type":"otp","scopes":[]}';
$login = request($url, $data, $headers);
if(strpos($login, '"access_token":"')!==false)
{
    $newAccessToken = getstr($login, '"access_token":"','"');
    $filename = 'config.php';
    $contents = file_get_contents($filename);
    $contents = str_replace($accessToken, $newAccessToken, $contents);
    file_put_contents($filename,$contents);
    $contents = file_get_contents($filename);
    $contents = str_replace($session, $guid1, $contents);
    file_put_contents($filename,$contents);
    $contents = file_get_contents($filename);
    $contents = str_replace($uniqueid, $guid2, $contents);
    file_put_contents($filename,$contents);
    echo "Success update access token to config.php\n";
}
else
{
    echo "Error Login\n";
}
