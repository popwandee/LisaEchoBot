<?php
function save_record_image($image,$name = null){
  $IMGBB_API_KEY = '6c23a11220bb2c1f7b9406175f3b8cbc';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://api.imgbb.com/1/upload?key='.$IMGBB_API_KEY);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
  //$extension = pathinfo($image['name'],PATHINFO_EXTENSION);
 // $file_name = ($name)? $name.'.'.$extension : $image['name'] ;
  $data = array('image' => base64_encode(file_get_contents($image['tmp_name'])), 'name' => $name);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  $result = curl_exec($ch);
  if (curl_errno($ch)) {
      return 'Error:' . curl_error($ch);
  }else{
    return json_decode($result, true);
  }
  curl_close($ch);
}


?>
