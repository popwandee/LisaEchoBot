<?php

$tz_object = new DateTimeZone('Asia/Bangkok');
$datetime = new DateTime();
$datetime->setTimezone($tz_object);
$dateTimeToday = $datetime->format('Ymd');
$dateNow = $datetime->format('YmdHis');
$dateTimeNow = $datetime->format('Ymd H:i:s');

?>
