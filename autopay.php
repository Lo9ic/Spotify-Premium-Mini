<?php
//Lo9ic who ?
function request($url, $data = null, $headers = null, $patch = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    if($data):
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    endif;
    if($patch):
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $patch);
    endif;
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if($headers):
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    endif;
        curl_setopt($ch, CURLOPT_HEADER, 1);

    curl_setopt($ch, CURLOPT_ENCODING, "GZIP");
    return curl_exec($ch);
}

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

function getstr($str, $exp1, $exp2)
{
    $a = explode($exp1, $str)[1];
    return explode($exp2, $a)[0];
}



include "config.php";

$headers = array();
$headers[] = "Pin: $pin";
$headers[] = "X-Appversion: 4.46.2";
$headers[] = "X-Appid: com.gojek.app";
$headers[] = "Authorization: Bearer $accessToken";
$headers[] = "X-Session-Id: $session";
$headers[] = "X-Platform: iOS";
$headers[] = "X-User-Locale: en_ID";
$headers[] = "X-Uniqueid: $uniqueid";
$headers[] = "Accept: application/json";
$headers[] = "X-User-Type: customer";
$headers[] = "X-Deviceos: iOS, 15.5";
$headers[] = "X-Phonemake: Apple";
$headers[] = "X-Phonemodel: Apple, iPhone 13 Pro";
$headers[] = "Content-Type: application/json; charset=UTF-8";
$headers[] = "Accept-Encoding: gzip, deflate";
$headers[] = "User-Agent: okhttp/3.12.13";
$headers[] = "Gojek-Country-Code: ID";


echo "Check GOPAY Account : ";
$url = "https://customer.gopayapi.com/v1/customer/payment-options?intent=DYNAMIC_QR";
$getPaymentToken = request($url, null, $headers);
if(strpos($getPaymentToken, '"success": true')!==false)
{
    $paymentToken = getstr($getPaymentToken, '"token": "','"');
    echo color("green", "Ready\n");
    
}
else
{
    echo "Error get Payment Token\n";
    exit();
}

$file = file_get_contents("gopay.txt");
    $datax = explode("\n",$file);
    $count = count($datax);
    for($a=0;$a<$count;$a++){
        $QRresult = $datax[$a];
$url = "https://customer.gopayapi.com/v1/explore";
$data = '{"data":"'.$QRresult.'","type":"QR_CODE"}';
$getPaymentID = request($url, $data, $headers);
if(strpos($getPaymentID, 'payment_id')!==false)
{
    $paymentID = getstr($getPaymentID, '"payment_id": "','"');
    $value = getstr($getPaymentID, 'value\":\"','\"');
    echo "$paymentID : ";

}
else
{
    echo "Error get Payment ID\n";
    exit();
    
}

$url = "https://customer.gopayapi.com/v2/payments/$paymentID/capture";
$patch = '{"additional_data":{"merchant_order_id":"","customer_flow":"qr","aspiqr_information":{"additional_data_national":"61051216062390703A015028'.$paymentID.'","merchant_city":"JAKARTA SELATAN","retrieval_reference_number":"","transaction_currency_code":"360","merchant_id":"G775528207","purpose_of_transaction":"","store_label":"","terminal_label":"A01","bill_number":"","qr_transaction_type":"ON-US","loyalty_number":"","merchant_criteria":"UBE","reference_label":"","merchant_pan":"936009143775528207","additional_consumer_data_request":"","merchant_category_code":"5733","trx_fee_amount":0.0,"merchant_name":"Spotify","issuer_name":"gopay","issuer_id":"93600914","acquirer_name":"gopay","country_code":"ID","acquirer_id":"93600914","customer_label":"","postal_code":"12160","mobile_number":""}},"applied_promo_code":["NO_PROMO_APPLIED"],"channel_type":"DYNAMIC_QR","checksum":{"version":"3","value":"'.$value.'"},"metadata":{"merchant_cross_reference_id":"5d5fa406-7085-4fb7-bee1-5fc649e15ef3","payment_widget_intent":"DYNAMIC_QR","aspi_qr_acquirer":"gopay","aspi_qr_data":"{\"amount\":800,\"postal_code\":\"12160\",\"merchant_city\":\"JAKARTA SELATAN\",\"merchant_id\":\"G775528207\",\"merchant_criteria\":\"UBE\",\"merchant_pan\":\"936009143775528207\",\"country_code\":\"ID\",\"transaction_currency_code\":\"360\",\"additional_data_national\":\"61051216062390703A015028'.$paymentID.'\",\"additional_data\":{\"store_label\":null,\"mobile_number\":null,\"reference_label\":null,\"purpose_of_transaction\":null,\"customer_label\":null,\"terminal_label\":\"A01\",\"bill_number\":null,\"custom_50\":\"'.$paymentID.'\",\"additional_consumer_data_request\":null,\"loyalty_number\":null},\"merchant_category_code\":\"5733\",\"merchant_name\":\"Spotify\",\"trx_fee_amount\":0,\"acquirer_id\":\"93600914\"}","checksum":"{\"version\":\"3\",\"value\":\"'.$value.'\"}","external_merchant_name":"Spotify","customer_flow":"qr","aspi_qr_transaction_type":"ON-US","aspi_qr_issuer":"gopay"},"order_signature":{"reason":"","partner_id":"","partner_name":"","source":"","channel_type":"","transaction_type":"","customer_fulfillment_type":""},"payment_token":"'.$paymentToken.'"}';
$pay = request($url, null, $headers, $patch);
if(strpos($pay, '"PAID"')!==false)
{
    echo color("green", "Success Pay\n");
}
else
{
    echo color("red", "Error Pay\n");
}
    }
