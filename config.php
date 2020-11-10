<?php

$tz_object = new DateTimeZone('Asia/Bangkok');
$datetime = new DateTime(); $datetime->setTimezone($tz_object);
$dateTimeToday = $datetime->format('Y-m-d');

\Cloudinary::config(array(
    'cloud_name' => 'dly6ftryr',
    'api_key' => '979642835457647',
    'api_secret' => 'hOm8JS7iHanEnFaqB9Y7qDwT5CQ'
));

define("MLAB_API_KEY", '6QxfLc4uRn3vWrlgzsWtzTXBW7CYVsQv');
define("LINE_MESSAGING_API_CHANNEL_SECRET", 'aa73290c8ac23efff7c79f796b80824c');
define("LINE_MESSAGING_API_CHANNEL_TOKEN", '14lyWNnWlGRrrXdW5/dW5VKNy2NpTKlg/P1oolT3O3olzt3OR1LDK9G0y7mUBrMXtxPePUIHPWdylLdkROwbOESi4rQE3+oSG3njcFj7yoS5JYgnXlnmwrTmlKC4fs2bjYk8sKUqboRSYUuPnOKXawdB04t89/1O/w1cDnyilFU=');

?>
