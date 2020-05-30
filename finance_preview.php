<?php
// Initialize the session
session_start();
/*
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
if(!isset($_SESSION["type"]) || $_SESSION["type"] == "สมาชิก"){
    header("location: index.php?message=You are not admin.");
    exit;
}
*/
// Include config file
require_once "config.php";
require_once "vendor/autoload.php";
require_once "vendor/settings.php";
require_once "vendor/function.php";

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="AFAPS40-CRMA51 Website">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>AFAPS40-CRMA51</title>
    <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

  <!-- Latest compiled and minified JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

      <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
      <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
      <script src="vendor/bootstrap/ie-emulation-modes-warning.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
  <?php include 'navigation.html';?>
    <div class="container theme-showcase" role="main">
    <div class="jumbotron">
      <h1>AFAPS40 - CRMA51</h1>
      <p>เตรียมทหาร รุ่นที่ 40 จปร.รุ่นที่ 51</p>
	  <?php

// from financemanager
      $_id = isset($_GET['_id']) ? $_GET['_id'] : "";
      if(!empty($_id)){
        if(isset($_SESSION['type']) && (($_SESSION['type'])=='admin')){
          // check if from formSubmit
          if(isset($_POST['formSubmit'])){
            $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/finance/'.$_id.'?apiKey='.MLAB_API_KEY);
            $data = json_decode($json);
            $isData=sizeof($data);
            if($isData >0){
              //echo "\nGet data from DB are "; //print_r($data);
                 $record=$data->record;$update_record = isset($_POST['record']) ? $_POST['record'] : "";
                 if($record!=$update_record){update_field($_id,'record',$update_record);}

                 $add=$data->add;$update_add = isset($_POST['add']) ? $_POST['add'] : "";
                 if($add!=$update_add){update_field($_id,'add',$update_add);}

                 $sub=$data->sub;$update_sub = isset($_POST['sub']) ? $_POST['sub'] : "";
                 if($sub!=$update_sub){update_field($_id,'sub',$update_sub);}

                 $detail=$data->detail;$update_detail = isset($_POST['detail']) ? $_POST['detail'] : "";
                 if($detail!=$update_detail){update_field($_id,'detail',$update_detail);}

                 if (!empty($_FILES['record_image'])) { //record_image
                   $return = save_record_image($_FILES['record_image'],'');
                   $img_url=$return['data']['image']['url'];
                   if(!empty($img_url)){
                     update_field($_id,'img_url',$img_url);
                   }
                 }

            }// end if isData > 0
          }else{
                show_form($_id);
          }// end if isset formSubmit

        }else{ // not a financemanager
              showdata($_id);
        }// end is financemanager
      }// end if not empty $_id



     ?>
     <?php
function showdata($_id)
  {
       //echo "\nin Fuction Show form, get user_id is ";print_r($user_id);
     $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/finance/'.$_id.'?apiKey='.MLAB_API_KEY);
     $data = json_decode($json);
     $isData=sizeof($data);
     if($isData >0){
       //echo "\nGet data from DB are "; //print_r($data);
       $username=$rec->username;
       $record=$rec->record;
       $add=$rec->add;
       $sub=$rec->sub;
       $sum=$sum+$add-$sub;
       $img_url=$rec->img_url;
       $detail=$rec->detail;
             ?>
             <table  class='table table-hover table-responsive table-bordered'>
               <tr><td width='50%'>
             <table  class='table table-hover table-responsive table-bordered'>
                 <tr>
                     <td>รายการ</td>
                     <td><?php echo $record;?></td>
                 </tr>
                 <tr>
                     <td>รายรับ</td>
                     <td><?php echo $add;?></td>
                     </tr>
                 <tr>
                     <td>รายจ่าย</td>
                     <td><?php echo $sub;?></td>
                 </tr>
                 <tr>
                     <td colspan="2">รายละเอียด/หมายเหตุ =>
                     <?php echo $detail;?></td>
                     </tr>
                 <tr>
     </table></td>
     <td  width="50%" align="center"><img src="<?php echo $img_url;?>" width='300'></td>
   </tr></table>

           <?php
         }// if isData > 0;
 } // end function showdata ?>

 <?php
function show_form($_id){
  //echo "\nin Fuction Show form, get user_id is ";print_r($user_id);
  $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/finance/'.$_id.'?apiKey='.MLAB_API_KEY);
$data = json_decode($json);
$isData=sizeof($data);
if($isData >0){print_r($data);
  //echo "\nGet data from DB are "; //print_r($data);
  $username=$rec->username;
  $record=$rec->record;
  $add=$rec->add;
  $sub=$rec->sub;
  $sum=$sum+$add-$sub;
  $img_url=$rec->img_url;
  $detail=$rec->detail;
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
        <table  class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>รายการเคลื่อนไหว</td>
                <td><input type='text' name='record' value="<?php echo $record;?>" class='form-control' /></td>
                </tr>
            <tr>
            <tr>
                <td>รายรับ</td>
                <td><input type='text' name='LineID' value="<?php echo $add;?>" class='form-control' /></td>
                </tr>
            <tr>
                <td>รายจ่าย</td>
                <td><input type='text' name='Tel1' value="<?php echo $sub;?>" class='form-control' /></td>
                </tr>
            <tr>
                <td>รายละเอียด/หมายเหตุ</td>
                <td><textarea name="detail" rows="3" cols="30"class='form-control' /><?php echo $detail;?></textarea></td>
            </tr>
        <tr>
            <td>เปลี่ยนรูปภาพ</td>
            <td><input type='file' name='record_image' class='form-control' /></td>
            </tr>
            <tr><td colspan="2"><img src="<?php echo $img_url;?>"></td></tr>
            <tr><td colspan="2">
              <input type="hidden"name="_id" value="<?php echo $_id;?>">
              <input type="hidden"name="formSubmit" value="true">
              <input type='submit' value='Save' class='btn btn-primary' /></td></tr>
</table>
</form>
      <?php
    }// if isData > 0;
else{echo "not found data";}
} // end function show_form

  ?>

<?php
function update_field($_id,$field_name,$new_info){

        $newData = '{ "$set" : { "'.$field_name.'" : "'.$new_info.'"} }';
        $opts = array('http' => array( 'method' => "PUT",
                                       'header' => "Content-type: application/json",
                                       'content' => $newData
                                                   )
                                                );
        $url = 'https://api.mlab.com/api/1/databases/crma51/collections/finance/'.$_id.'?apiKey='.MLAB_API_KEY;
                $context = stream_context_create($opts);
                $returnValue = file_get_contents($url,false,$context);

}
 ?>
         <div><!-- class="jumbotron"-->
      </div> <!-- container theme-showcase -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
