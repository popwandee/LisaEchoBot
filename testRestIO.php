
<?php 
$url="https://area51-dfba.restdb.io/rest/people";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','x-apikey: d376fad73437100d1a070319e29d9b12fbe21')')
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $return_data = curl_exec($ch);
            curl_close($ch);
            return json_decode($return_data,TRUE);
            
            print_r($return_data);
            
?>
