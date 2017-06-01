<?php

include("./includes/blowfish.class.php");

$MerchantID = "";                // via e-mail from computop support
$BlowfishPassword = "";   // via phone from computop support
$HmacPassword = "";           // via phone from computop support
$BASE_URL = "https://localhost/ct/";

mt_srand((double)microtime() * 1000000);
$sTransID = (string)mt_rand();
$sTransID .= date("yzGis");
$TransID = $sTransID;

$Amount = "1609";
$Currency = "EUR";
$OrderDesc = "Test";
$URLSuccess = $BASE_URL . "success.php";
$URLFailure = $BASE_URL . "failure.php";
$URLNotify = $BASE_URL . "notify.php";
$UserData = "user data";

$Response = "encrypt";
$Capture = "AUTO";

//Create parameters

$pPayPalMethod = "PayPalMethod=shortcut";
$pTransID = "TransID=$TransID";
$pAmount = "Amount=$Amount";
$pCurrency = "Currency=$Currency";
$pURLSuccess = "URLSuccess=$URLSuccess";
$pURLFailure = "URLFailure=$URLFailure";
$pURLNotify = "URLNotify=$URLNotify";
$pOrderDesc = "OrderDesc=$OrderDesc";
$pUserData = "UserData=$UserData";
$pCapture = "Capture=$Capture";
$pResponse = "Response=$Response";

//Creating MAC value

$MAC = hash_hmac("sha256", "*$TransID*$MerchantID*$Amount*$Currency", $HmacPassword);
$pMAC = "MAC=$MAC";

$query = array($pTransID, $pAmount, $pCurrency, $pURLSuccess, $pURLFailure, $pURLNotify, $pOrderDesc, $pUserData, $pCapture, $pResponse, $pMAC);

$plaintext = join("&", $query);
$Len = strlen($plaintext);  // Length of the plain text string


$bf = new Blowfish();

$plaintext = $bf->expand($plaintext);
$bf->bf_set_key($BlowfishPassword);
$Data = bin2hex($bf->encrypt($plaintext));

//Build Final URL Params
$pLen = "Len=$Len";
$pData = "Data=$Data";
$pMerchantID = "MerchantID=$MerchantID";


$urlParams = array($pMerchantID, $pData, $pLen);
$finalParams = implode("&", $urlParams);

$url = "https://www.computop-paygate.com/paypal.aspx";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $finalParams);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

$res = curl_exec($ch);
$curl_info = curl_getinfo($ch);

$parts = parse_url($curl_info["redirect_url"]);
parse_str($parts['query'], $query);

//echo $query['token'];

echo '{"paymentToken":"' . $query['token'] . '"}';

//header('Location: ' . $curl_info["redirect_url"]) ;

?>
