<?PHP
$json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY);
$data = json_decode($json);
$isData=sizeof($data);
if($isData >0){
  $i=0;
  foreach($data as $rec){
    $i++;
    $_id=$rec->_id;
    foreach($_id as $rec_id){
    $_id=$rec_id;
    }
     $type=$rec->type;
     if($rank=="normaluser"){update_field($_id,'type','สมาชิก');}

     $approved=$rec->approved;
     if($approved==0){update_field($_id,'approved','1');}
  }//end if
  ?>
  <?php
  function update_field($user_id,$field_name,$new_info){

          $newData = '{ "$set" : { "'.$field_name.'" : "'.$new_info.'"} }';
          $opts = array('http' => array( 'method' => "PUT",
                                         'header' => "Content-type: application/json",
                                         'content' => $newData
                                                     )
                                                  );
          $url = 'https://api.mlab.com/api/1/databases/crma51/collections/friend/'.$user_id.'?apiKey='.MLAB_API_KEY;
                  $context = stream_context_create($opts);
                  $returnValue = file_get_contents($url,false,$context);

  }
   ?>
