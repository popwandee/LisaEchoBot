<?php
$tz_object = new DateTimeZone('Asia/Bangkok');
$datetime = new DateTime();
$datetime->setTimezone($tz_object);
$dateTimeToday = $datetime->format('Ymd');
$dateNow = $datetime->format('YmdHis');
$dateTimeNow = $datetime->format('Ymd H:i:s');


\Cloudinary::config(array(
    'cloud_name' => 'crma51',
    'api_key' => '486757946979428',
    'api_secret' => 'cT7-rRMQmkCINwzvaVdFIy_SaUU'
));

define("LINE_MESSAGING_API_CHANNEL_SECRET", 'aa73290c8ac23efff7c79f796b80824c');
define("LINE_MESSAGING_API_CHANNEL_TOKEN", '14lyWNnWlGRrrXdW5/dW5VKNy2NpTKlg/P1oolT3O3olzt3OR1LDK9G0y7mUBrMXtxPePUIHPWdylLdkROwbOESi4rQE3+oSG3njcFj7yoS5JYgnXlnmwrTmlKC4fs2bjYk8sKUqboRSYUuPnOKXawdB04t89/1O/w1cDnyilFU=');

?>
