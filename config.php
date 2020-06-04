<?php

$tz_object = new DateTimeZone('Asia/Bangkok');
$datetime = new DateTime(); $datetime->setTimezone($tz_object); $dateTimeToday = $datetime->format('Y-m-d');

\Cloudinary::config(array(
    'cloud_name' => '',
    'api_key' => '',
    'api_secret' => ''
));

define("MLAB_API_KEY", '');
define("LINE_MESSAGING_API_CHANNEL_SECRET", '');
define("LINE_MESSAGING_API_CHANNEL_TOKEN", '14lyWNnWlGRrrXdW5/dW5VKNy2NpTKlg/+oSG3njcFj7yoS5JYgnXlnmwrTmlKC4fs2bjYk8sKUqboRSYUuPnOKXawdB04t89/1O/w1cDnyilFU=');

?>
