<?php
function color($color, $text)
{
    $arrayColor = array(
        'grey'      => '1;30',
        'red'       => '1;31',
        'green'     => '1;32',
        'yellow'    => '1;33',
        'blue'      => '1;34',
        'purple'    => '1;35',
        'nevy'      => '1;36',
        'white'     => '1;0',
    );
    return "\033[" . $arrayColor[$color] . "m" . $text . "\033[0m";
}

function request($url, $data = null, $headers = null, $patch = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    if($data):
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    endif;
    if($patch):
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $delete);
    endif;
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if($headers):
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    endif;
        curl_setopt($ch, CURLOPT_HEADER, 1);

    curl_setopt($ch, CURLOPT_ENCODING, "GZIP");
    return curl_exec($ch);
}

function getstr($str, $exp1, $exp2)
{
    $a = explode($exp1, $str)[1];
    return explode($exp2, $a)[0];
}

include "config.php";
echo "Get Berapa Link : ";
$loop = trim(fgets(STDIN));
for($a=0;$a<$loop;$a++){
awal:
$url = "https://www.spotify.com/id/api/payment-sdk/prepare/premium/?clientName=premium-www-checkout&clientContext=premium-checkout&version=4.8.0";
$data = "------WebKitFormBoundaryY0KA7jDmXWNKQADE\r\nContent-Disposition: form-data; name=\"checkout.paymentProviderId\"\r\n\r\nbilling_boku_gopay_rpsp\r\n------WebKitFormBoundaryY0KA7jDmXWNKQADE\r\nContent-Disposition: form-data; name=\"checkout.checkoutViewId\"\r\n\r\n9ac28648-6890-4a62-aa76-20a1812f5f1d\r\n------WebKitFormBoundaryY0KA7jDmXWNKQADE\r\nContent-Disposition: form-data; name=\"checkout.attemptId\"\r\n\r\n305919bd-65ee-4b32-b36a-8c0b285709d1\r\n------WebKitFormBoundaryY0KA7jDmXWNKQADE\r\nContent-Disposition: form-data; name=\"checkout.flowId\"\r\n\r\n7beedd4b-931e-4484-b81f-bc4c0b2073a6\r\n------WebKitFormBoundaryY0KA7jDmXWNKQADE\r\nContent-Disposition: form-data; name=\"checkout.country\"\r\n\r\nID\r\n------WebKitFormBoundaryY0KA7jDmXWNKQADE\r\nContent-Disposition: form-data; name=\"checkout.returnUrl\"\r\n\r\nhttps://www.spotify.com/id/purchase/continue/purchase/premium_mini_7d/?orderReference={checkoutId}&offerId=premium-mini-7d\r\n------WebKitFormBoundaryY0KA7jDmXWNKQADE\r\nContent-Disposition: form-data; name=\"checkout.originUrl\"\r\n\r\nhttps://www.spotify.com/id/purchase/offer/2022-artist-mini-premium-mini-7d/?campaign=artist\r\n------WebKitFormBoundaryY0KA7jDmXWNKQADE\r\nContent-Disposition: form-data; name=\"checkout.offerCountryProductId\"\r\n\r\ndefault:premium-mini-7d:3\r\n------WebKitFormBoundaryY0KA7jDmXWNKQADE\r\nContent-Disposition: form-data; name=\"checkout.offerUuid\"\r\n\r\naa72c91c-7e0a-463e-b026-1ce46b9b3b5d\r\n------WebKitFormBoundaryY0KA7jDmXWNKQADE\r\nContent-Disposition: form-data; name=\"checkout.offerKey\"\r\n\r\npremium_mini_7d\r\n------WebKitFormBoundaryY0KA7jDmXWNKQADE\r\nContent-Disposition: form-data; name=\"checkout.productDescription\"\r\n\r\nSpotify 7 hari\r\n------WebKitFormBoundaryY0KA7jDmXWNKQADE\r\nContent-Disposition: form-data; name=\"checkout.isChangeDetails\"\r\n\r\nfalse\r\n------WebKitFormBoundaryY0KA7jDmXWNKQADE--\r\n";
$headers = array();
$headers[] = 'Cookie: '.$cookie.'';
$headers[] = 'Sec-Ch-Ua: "Chromium";v="103", ".Not/A)Brand";v="99"';
$headers[] = 'Content-Type: multipart/form-data; boundary=----WebKitFormBoundaryY0KA7jDmXWNKQADE';
$headers[] = 'X-Csrf-Token: '.$csrf.'';
$headers[] = 'Sec-Ch-Ua-Mobile: ?0';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.5060.53 Safari/537.36';
$headers[] = 'Sec-Ch-Ua-Platform: "macOS"';
$headers[] = 'Accept: */*';
$headers[] = 'Origin: https://www.spotify.com';
$headers[] = 'Sec-Fetch-Site: same-origin';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Sec-Fetch-Dest: empty';
$headers[] = 'Referer: https://www.spotify.com/id/purchase/offer/2022-artist-mini-premium-mini-7d/?campaign=artist';
$headers[] = 'Accept-Encoding: gzip, deflate';
$headers[] = 'Accept-Language: en-US,en;q=0.9';
$getlink = request($url, $data, $headers);
if(strpos($getlink, 'success":true')!==false)
{
    $trxID = getstr($getlink, 'checkoutidentify/','/');
}
else
{
    echo "Error\n";
    goto awal;
}

$url = "https://buy2.boku.com/transaction/$trxID/purchase";
$headers = array();
$headers[] = "Accept: application/json, text/plain, */*";
$headers[] = "Content-Type: application/x-www-form-urlencoded; charset=UTF-8";
$headers[] = "Sec-Ch-Ua-Mobile: ?0";
$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.5060.53 Safari/537.36";
$headers[] = "Sec-Fetch-Site: same-origin";
$headers[] = "Sec-Fetch-Mode: cors";
$headers[] = "Sec-Fetch-Dest: empty";
$headers[] = "Accept-Encoding: gzip, deflate";
$headers[] = "Accept-Language: en-US,en;q=0.9";
$headers[] = "Connection: close";
$data = "network=id-gopay&remember-msisdn=0&deviceFingerprint=undefined";
$inputTrxID = request($url, $data, $headers);
if(strpos($inputTrxID, "$trxID")!==false)
{

}
else
{
    echo "Error Line 80\n";
    goto awal;
}

$url = "https://buy.boku.com/checkout-aux/receive-carrier-flow/transaction-status?redirect-id=&transaction-id=$trxID";
$headers = array();
$headers[] = "Accept: application/json, text/plain, */*";
$headers[] = "Content-Type: application/x-www-form-urlencoded; charset=UTF-8";
$headers[] = "Sec-Ch-Ua-Mobile: ?0";
$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.5060.53 Safari/537.36";
$headers[] = "Sec-Fetch-Site: same-origin";
$headers[] = "Sec-Fetch-Mode: cors";
$headers[] = "Sec-Fetch-Dest: empty";
$headers[] = "Accept-Encoding: gzip, deflate";
$headers[] = "Accept-Language: en-US,en;q=0.9";
$headers[] = "Connection: close";
$getQR = request($url, $data = null, $headers);
if(strpos($getQR, '"url":"https')!==false)
{
    $QR = getstr($getQR, '"url":"','"');
    // echo $QR;
}
else
{
    echo "Error Line 105\n";
    goto awal;
}


$url = "https://zxing.org/w/decode?u=$QR";
$scanQR = request($url, $data = null, $headers = null);
if(strpos($scanQR, 'COM.GO-JEK')!==false)
{
    $QRresult = getstr($scanQR, 'Raw text</td><td><pre>','<');
    file_put_contents("gopay.txt","$QRresult\n", FILE_APPEND);
    echo color("green", "Success Get Link\n");
}
else
{
    echo "Error Scan QR\n";
}

}
