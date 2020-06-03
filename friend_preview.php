<?php
// Initialize the session
session_start();

// Cloudinary
require 'vendor/cloudinary/cloudinary_php/src/Cloudinary.php';
require 'vendor/cloudinary/cloudinary_php/src/Uploader.php';
require 'vendor/cloudinary/cloudinary_php/src/Api.php';

// Include config file
require_once "config.php";// mlab
require_once "vendor/autoload.php";
require_once "vendor/function.php";

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : "";
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
    // ตรวจสอบ $_id จาก _GET และ _POST
          if(isset($_GET['_id'])){
            $_id = $_GET['_id'];
          }elseif(isset($_POST['_id'])){
            $_id = $_POST['_id'];
          }else{$_id="";}

          if(!empty($_id)){
            if(isset($_GET['action'])){
              $action = $_GET['action'] ? $_GET['action'] : "";
              switch ($action) {
                case 'preview':
                  showdata($_id);
                  break;
                case 'form':
                  show_form($_id);
                  break;
                default:
                  showdata($_id);
                  break;
              }// end switch
            }// end if isset action

            if(($user_id == $_id) || (($_SESSION['type'])=='admin')){
              // check if from formSubmit
              if(isset($_POST['formSubmit'])){
                $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend/'.$_id.'?apiKey='.MLAB_API_KEY);
                $data = json_decode($json);
                $isData=sizeof($data);
                if($isData >0){
                  //echo "\nGet data from DB are "; //print_r($data);
                     $rank=$data->rank;$update_rank = isset($_POST['rank']) ? $_POST['rank'] : "";
                     if($rank!=$update_rank){update_field($_id,'rank',$update_rank);}

                     $name=$data->name;$update_name = isset($_POST['name']) ? $_POST['name'] : "";
                     if($name!=$update_name){update_field($_id,'name',$update_name);}

                     $lastname=$data->lastname;$update_lastname = isset($_POST['lastname']) ? $_POST['lastname'] : "";
                     if($lastname!=$update_lastname){update_field($_id,'lastname',$update_lastname);}

                     $position=$data->position;$update_position = isset($_POST['position']) ? $_POST['position'] : "";
                     if($position!=$update_position){update_field($_id,'position',$update_position);}

                     $province=$data->province;$update_province = isset($_POST['province']) ? $_POST['province'] : "";
                     if($province!=$update_province){update_field($_id,'province',$update_province);}

                     $Email=$data->Email;$update_Email = isset($_POST['Email']) ? $_POST['Email'] : "";
                     if($Email!=$update_Email){update_field($_id,'Email',$update_Email);}

                     $Tel1=$data->Tel1;$update_Tel1 = isset($_POST['Tel1']) ? $_POST['Tel1'] : "";
                     if($Tel1!=$update_Tel1){update_field($_id,'Tel1',$update_Tel1);}

                     $LineID=$data->LineID;$update_LineID = isset($_POST['LineID']) ? $_POST['LineID'] : "";
                     if($LineID!=$update_LineID){update_field($_id,'LineID',$update_LineID);}

                     $comment=$data->comment;$update_comment = isset($_POST['comment']) ? $_POST['comment'] : "";
                     if($comment!=$update_comment){update_field($_id,'comment',$update_comment);}

                     $type = $data->type;$update_type = isset($_POST['type']) ? $_POST['type'] : "";
                     if($type!=$update_type){update_field($_id,'type',$update_type);}

                     if (!empty($_FILES["record_image"]['tmp_name'])) { //record_image
                       $files = $_FILES["record_image"]['tmp_name'];
                       $cloudUpload = \Cloudinary\Uploader::upload($files);
                       $img_url = $cloudUpload['secure_url'];
                        if(!empty($img_url)){
                         update_field($_id,'img_url',$img_url);
                       }
                     }
                     // retrieve database
                         showdata($_id);
                   }else{
                  $_SESSION['message']=$_SESSION['message']." ไม่พบข้อมูลในฐานข้อมูลที่ต้องการแก้ไข/";
                }// end if isData > 0
              }else{ // ไม่ได้มาจากหอร์ม แสดงฟอร์ม
                echo "<a href='friend_preview.php?_id=$_id&action=preview'><button type='button' class='btn btn-xs btn-success'>แสดงตัวอย่าง</button></a>";
                if($user_id==$_id){ echo "คุณสามารถแก้ไขข้อ มูลตัวเองได้";}
                if(($_SESSION['type'])=='admin'){echo "Admin แก้ไขข้อมูลเพื่อน ๆ ได้";}
                show_form($_id);
              }// end if isset formSubmit

            }else{ // not admin or own data
              echo "<a href='friend_preview.php?_id=$_id&action=form'><button type='button' class='btn btn-xs btn-success'>แก้ไข (เฉพาะ Admin และเจ้าของข้อมูล)</button></a>";
              showdata($_id);
            }

          }// end if not empty $_id

     ?>

     <?php
function showdata($_id)
  {
       //echo "\nin Fuction Show form, get user_id is ";print_r($user_id);
     $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend/'.$_id.'?apiKey='.MLAB_API_KEY);
     $data = json_decode($json);
     $isData=sizeof($data);
     if($isData >0){
       //echo "\nGet data from DB are "; //print_r($data);
          $rank=$data->rank;
          $name=$data->name;
          $lastname=$data->lastname;
          $position=$data->position;
          $province=$data->province;
          $Email=$data->Email;
          $Tel1=$data->Tel1;
          $LineID=$data->LineID;
          $comment=$data->comment;
          $type = $data->type;
          $img_url=$data->img_url;
             ?>
             <table  class='table table-hover table-responsive table-bordered'>
               <tr><td>
             <table  class='table table-hover table-responsive table-bordered'>
                 <tr>
                     <td>ยศ ชื่อ สกุล</td>
                     <td><?php echo $rank;?><?php echo $name;?><?php echo $lastname;?></td>
                 </tr>
                 <tr>
                     <td>ตำแหน่ง</td>
                     <td><?php echo $position;?></td>
                     </tr>
                 <tr>
                     <td>จังหวัด</td>
                     <td><?php echo $province;?></td>
                 </tr>
                 <tr>
                     <td>Email</td>
                     <td><?php echo $Email;?></td>
                     </tr>
                 <tr>
                 <tr>
                     <td>LINE ID</td>
                     <td><?php echo $LineID;?></td>
                     </tr>
                 <tr>
                     <td>โทรศัพท์</td>
                     <td><?php echo $Tel1;?></td>
                     </tr>
                 <tr>
                     <td>รายละเอียดเพิ่มเติม</td>
                     <td><?php echo $comment;?></td>
                 </tr>
     </table></td>
     <td align="center"><img src="<?php echo $img_url;?>" width='300'></td>
   </tr>
 </table>

           <?php
         }// if isData > 0;
 } // end function showdata ?>

 <?php
function show_form($_id){
  //echo "\nin Fuction Show form, get user_id is ";print_r($user_id);
  $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend/'.$_id.'?apiKey='.MLAB_API_KEY);

$data = json_decode($json);
$isData=sizeof($data);
if($isData >0){
  //echo "\nGet data from DB are "; //print_r($data);
     $rank=$data->rank;
     $name=$data->name;
     $lastname=$data->lastname;
     $position=$data->position;
     $province=$data->province;
     $Email=$data->Email;
     $Tel1=$data->Tel1;
     $LineID=$data->LineID;
     $comment=$data->comment;
     $type = $data->type;
     $img_url=$data->img_url;
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
        <table><tr><td>
        <table  class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>ยศ ชื่อ สกุล</td>
                <td>
                  <select name="rank">
            <option value="<?php echo $rank;?>" selected><?php echo $rank;?></option>
            <option value="ร.ต.">ร.ต.</option>
            <option value="ร.ท.">ร.ท.</option>
            <option value="ร.อ.">ร.อ.</option>
            <option value="พ.ต.">พ.ต.</option>
            <option value="พ.ท.">พ.ท.</option>
            <option value="พ.อ.">พ.อ.</option>
            <option value="พล.ต.">พล.ต.</option>
            <option value="พล.ท.">พล.ท.</option>
            <option value="พล.อ.">พล.อ.</option>
            </select>
                  <input type='text' name='name' value="<?php echo $name;?>" />
                  <input type='text' name='lastname' value="<?php echo $lastname;?>"  /></td>
            </tr>
            <tr>
                <td>ตำแหน่ง</td>
                <td><input type='text' name='position' value="<?php echo $position;?>" class='form-control' /></td>
                </tr>
            <tr>
                <td>จังหวัด</td>
                <td><?php select_province($province);?> </td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type='text' name='Email' value="<?php echo $Email;?>" class='form-control' /></td>
                </tr>
            <tr>
            <tr>
                <td>LINE ID</td>
                <td><input type='text' name='LineID' value="<?php echo $LineID;?>" class='form-control' /></td>
                </tr>
            <tr>
                <td>โทรศัพท์</td>
                <td><input type='text' name='Tel1' value="<?php echo $Tel1;?>" class='form-control' /></td>
                </tr>
            <tr>
                <td>รายละเอียดเพิ่มเติม</td>
                <td><textarea name="comment" rows="10" cols="30"class='form-control' /><?php echo $comment;?></textarea></td>
            </tr>
            <tr><td colspan="2">เปลี่ยนรูปภาพ<input type="file" name="record_image" class="form-control" accept="image/*"></td></tr>
</table></td>
<td><img src="<?php echo $img_url;?>" width="300"></td></tr>
<tr><td colspan="2">
<input type="hidden"name="_id" value="<?php echo $_id;?>">
  <input type="hidden"name="formSubmit" value="true">
  <input type='submit' value='Save' class='btn btn-primary' />
</td></tr></table>
      <?php
    }// if isData > 0;

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
        $url = 'https://api.mlab.com/api/1/databases/crma51/collections/friend/'.$_id.'?apiKey='.MLAB_API_KEY;
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
