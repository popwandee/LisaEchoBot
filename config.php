<?php
define("MLAB_API_KEY", '');
$tz_object = new DateTimeZone('Asia/Bangkok');
$datetime = new DateTime(); $datetime->setTimezone($tz_object); $dateTimeToday = $datetime->format('Y-m-d');

\Cloudinary::config(array(
    'cloud_name' => '',
    'api_key' => '',
    'api_secret' => ''
));
?>
