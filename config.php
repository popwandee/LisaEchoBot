<?php
define("MLAB_API_KEY", '6QxfLc4uRn3vWrlgzsWtzTXBW7CYVsQv');
$tz_object = new DateTimeZone('Asia/Bangkok');
$datetime = new DateTime(); $datetime->setTimezone($tz_object); $dateTimeToday = $datetime->format('Y-m-d');

\Cloudinary::config(array(
    'cloud_name' => 'dly6ftryr',
    'api_key' => '979642835457647',
    'api_secret' => 'hOm8JS7iHanEnFaqB9Y7qDwT5CQ'
));
?>
