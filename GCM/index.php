<?php
require_once  __DIR__."/GCMService.php";


$device_token = "TOKEN_1";
$device_token2 = "TOKEN_2";
$gcm_key = "GCM_KEY_OR_FCM_KEY";

$GCM = new GCMService($gcm_key);
$GCM->setContentTitle("_TITLE_");
$GCM->setContentText("_PUSH_TEXT_");
$GCM->addDevice($device_token);
$GCM->addDevice($device_token2);
$result = $GCM->makeNotify();
var_dump($result);
