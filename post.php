<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
// ตรวจสอบ ประเภทสมาชิก
if(isset($_SESSION['type'])){    $user_type = $_SESSION['type'];
}else{                           $user_type = "";
}


// Cloudinary
require 'vendor/cloudinary/cloudinary_php/src/Cloudinary.php';
require 'vendor/cloudinary/cloudinary_php/src/Uploader.php';
require 'vendor/cloudinary/cloudinary_php/src/Api.php';

// Include config file
require_once "config.php";// mlab
require_once "vendor/autoload.php";
require_once "vendor/function.php";

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Relax Website">
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
<script data-ad-client="ca-pub-0730772505870150" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</head>
<body>
    <?php include 'navigation.html';?>
    <?php
    // ตรวจสอบ $_id จาก _GET และ _POST
          if(isset($_GET['_id'])){         $_id = $_GET['_id'];
          }elseif(isset($_POST['_id'])){   $_id = $_POST['_id'];
          }else{                           $_id = "";
          }

      // ตรวจสอบ $_id จาก _GET และ _POST
                if(isset($_GET['img_url'])){         $img_url = $_GET['img_url'];
                }elseif(isset($_POST['img_url'])){   $img_url = $_POST['img_url'];
                }else{                               $img_url = "";
                }
  //      ตรวจสอบ Action จาก _GET หรือ _POST
          if(isset($_GET['action'])){         $action = $_GET['action'];
          }elseif(isset($_POST['action'])){   $action = $_POST['action'];
          }else{                              $action = "";
          }
          ?>
          <div class="container theme-showcase" role="main">
          <div class="jumbotron">
            <h1>POST</h1>
            <p>ประชาสัมพันธ์</p>
<?php // core logic

switch ($action) {
  case 'review':
    //echo "action is review and not empty _id is $_id, call function review_request";
    review_post($img_url);
    break;
  case 'newpost' :
    $name= isset($_SESSION['user_info']) ? htmlspecialchars($_SESSION['user_info']) : '';
    $title= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
    $detail= isset($_POST['detail']) ? htmlspecialchars($_POST['detail']) : '';
    $today = date("Ymd-His");
  $newData->name = $name;
  $newData->date = $today;
  $newData->title = $title;
  $newData->detail = $detail;

         if (!empty($_FILES['record_image'])) { //record_image
           $index=0;
           foreach ($_FILES["record_image"]['tmp_name'] as $files){
             $target_file = basename($_FILES["record_image"]["name"][$index]);
             $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
             if(!empty($imageFileType)){
               $public_id =$today."-".$index;
               $option=array("public_id" => $public_id);
               $file_name =$public_id.".".$imageFileType;
               $img_index = 'img_url-'.$index;
               $newData->$img_index=$file_name;
               $cloudUpload = \Cloudinary\Uploader::upload($files,$option);
               $index++;
             }

           }

         }// end if !empty _FILES

      insert_post($newData);
      //show_all_post();
    break; // end case newrequest
  case 'edited' :
    $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/post/'.$_id.'?apiKey='.MLAB_API_KEY);
    $data = json_decode($json);
    $isData=sizeof($data);
    if($isData >0){

     $name_db=$data->name;$update_name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : "";
     if($update_name!=$name_db){update_field($_id,'name',$update_name);}

     $title_db=$data->title;$update_title = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : "";
     if($update_title!=$title_db){update_field($_id,'title',$update_title);}

     $detail_db=$data->detail;$update_detail = isset($_POST['detail']) ? htmlspecialchars($_POST['detail']) : "";
     if($update_detail!=$detail_db){update_field($_id,'detail',$update_detail);}

     if (!empty($_FILES['record_image'])) { //record_image
       $files = $_FILES["record_image"]['tmp_name'];
       $cloudUpload = \Cloudinary\Uploader::upload($files);
       $img_url = htmlspecialchars($cloudUpload['secure_url']);
       if(!empty($img_url)){
         update_field($_id,'img_url',$img_url);
         //$_SESSION['message']=$_SESSION['message']." บันทึกรูปภาพ ".$img_url." แล้ว/";
       }
     }// end if !empty _FILES

    }// end if data>0
    $_SESSION['message']=$_SESSION['message']." แก้ไข/ปรับปรุงเรื่องแจ้งคณะกรรมการรุ่นเรียบร้อยแล้ว";
    new_post_form();
    break;
  default:
    new_post_form();
    break;
}//end switch action
    show_all_post();
?>
<div>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- lisa_echo_bot_website -->
<ins class="adsbygoogle"
    style="display:block"
    data-ad-client="ca-pub-0730772505870150"
    data-ad-slot="7819708405"
    data-ad-format="auto"
    data-full-width-responsive="true"></ins>
<script>
    (adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>
</div><!-- jumbotron-->
</div><!-- container theme-showcase-->

<?php
function show_all_post(){
      $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/post?apiKey='.MLAB_API_KEY);
      $data = json_decode($json);
      $isData=sizeof($data);
      if($isData >0){
        $i=0;
        ?>
          <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title">Gallery</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover table-striped">
                  <tbody>
        <?php
         $i=0;
           foreach($data as $rec){
           //echo "<br> record is ".$rec->date.$rec->name.$rec->title.$rec->detail;
           //print_r($rec);
             $img_index='img_url-0';$img_url0=$rec->$img_index;
             $img_index='img_url-1';$img_url1=$rec->$img_index;
             $img_index='img_url-2';$img_url2=$rec->$img_index;
             $img_index='img_url-3';$img_url3=$rec->$img_index;
             $img_index='img_url-4';$img_url4=$rec->$img_index;
             $i++;?>
      <tr><td class="text-nowrap"><a href="post.php?action=review&img_url=<?php echo $img_url0;?>" target="_blank">
        <?php echo cl_image_tag("$img_url0", array("width"=>100, "height"=>100,"radius"=>50, "gravity"=>"face", "crop"=>"thumb"));?></a></td>
        <td><a href="post.php?action=review&img_url=<?php echo $img_url0;?>" target="_blank">
        <?php echo cl_image_tag("$img_url0", array("width"=>100));?></a>
        <a href="post.php?action=review&img_url=<?php echo $img_url0;?>" target="_blank">
        <?php echo cl_image_tag("$img_url1", array("width"=>100));?></a>
        <a href="post.php?action=review&img_url=<?php echo $img_url0;?>" target="_blank">
        <?php echo cl_image_tag("$img_url2", array("width"=>100));?></a>
        <a href="post.php?action=review&img_url=<?php echo $img_url0;?>" target="_blank">
        <?php echo cl_image_tag("$img_url3", array("width"=>100));?></a>
        <a href="post.php?action=review&img_url=<?php echo $img_url0;?>" target="_blank">
        <?php echo cl_image_tag("$img_url4", array("width"=>100));?></a>
      </td>
      </tr>
      <tr><td><?php echo $rec->detail;?></td></tr>
           <?php    } //end foreach ?>
           </tbody>
         </table>
     </div><!-- class="table-responsive"> -->
     </div><!-- class="panel panel-success"> -->
           <?php
           }else{
           echo "ยังไม่มีข้อมูลค่ะ";
               }
             }// end function show_friend
               ?>

<?php
function new_post_form(){ ?>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
  <table class='table table-hover table-responsive' width="100">
    <tr><td colspan="2" align="center">ภาพประชาสัมพันธ์</td></tr>
    <tr><td colspan="2">รูปภาพ<input type='file' name='record_image[]' class='form-control' /></td></tr>
    <tr><td colspan="2"><input type='file' name='record_image[]' class='form-control' /></td></tr>
    <tr><td colspan="2"><input type='file' name='record_image[]' class='form-control' /></td></tr>
    <tr><td colspan="2"><input type='file' name='record_image[]' class='form-control' /></td></tr>
    <tr><td colspan="2"><input type='file' name='record_image[]' class='form-control' /></td></tr>
    <tr>
      <td>ชื่อภาพ</td><td><input type='text' name='title' class='form-control' /></td></tr>
      <td colspan="2">โพสต์<br>
          <textarea name="detail" rows="5" cols="10"class='form-control' /></textarea>
</td></tr>
      <tr><td colspan="2" align="center"><input type="hidden"name="action" value="newpost">
              <input type='submit' value='POST' class='btn btn-primary' /></td></tr>
      </table>
    </form>
<?php } // end request_form ?>

<?php
function review_post($img_url){ ?>
    <div class="panel panel-success">
    <div class="table-responsive">
          <table class="table table-sm table-hover table-striped">
          <tr><td align='center'><?php echo cl_image_tag("$img_url", array("width"=>500));?></td></tr>
          </table>
               </div>
               </div>
<?php }// end function review request  ?>

<?php function insert_post($newData){

$newData=json_encode($newData);
$opts = array('http' => array( 'method' => "POST",
                             'header' => "Content-type: application/json",
                             'content' => $newData
                                         )
                                      );

$url = 'https://api.mlab.com/api/1/databases/crma51/collections/post?apiKey='.MLAB_API_KEY.'';
      $context = stream_context_create($opts);
      $returnValue = file_get_contents($url,false,$context);

      if($returnValue){
     $message= "<div align='center' class='alert alert-success'>โพสต์ ".$title." เรียบร้อย</div>";
        }else{
     $message= "<div align='center' class='alert alert-danger'>ไม่สามารถโพสต์ได้</div>";
               }
    $_SESSION["message"]=$message;
      return;
}//end function insert_request
 ?>
 <?php
 function update_field($_id,$field_name,$new_info){

         $newData = '{ "$set" : { "'.$field_name.'" : "'.$new_info.'"} }';
         $opts = array('http' => array( 'method' => "PUT",
                                        'header' => "Content-type: application/json",
                                        'content' => $newData
                                                    )
                                                 );
         $url = 'https://api.mlab.com/api/1/databases/crma51/collections/post/'.$_id.'?apiKey='.MLAB_API_KEY;
                 $context = stream_context_create($opts);
                 $returnValue = file_get_contents($url,false,$context);
                 return;
 }
  ?>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
