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
    <?php
    // ตรวจสอบ $_id จาก _GET และ _POST
          if(isset($_GET['_id'])){         $_id = $_GET['_id'];
          }elseif(isset($_POST['_id'])){   $_id = $_POST['_id'];
          }else{                           $_id = "";
          }

  //      ตรวจสอบ Action จาก _GET หรือ _POST
          if(isset($_GET['action'])){         $action = $_GET['action'];
          }elseif(isset($_POST['action'])){   $action = $_POST['action'];
          }else{                              $action = "";
          }
          ?>
          <div class="container theme-showcase" role="main">
          <div class="jumbotron">
            <h1>AFAPS40 - CRMA51</h1>
            <p>เตรียมทหาร รุ่นที่ 40 จปร.รุ่นที่ 51</p>
                 <?php $message=isset($_SESSION['message']) ? $_SESSION['message'] : '';
                       echo $message;$_SESSION['message']='';?>

<?php // core logic

switch ($action) {
  case 'review':
    //echo "action is review and not empty _id is $_id, call function review_request";
    review_post($_id);
    show_all_post();
    break;
  case 'newpost' :
    $name= isset($_POST['name']) ? $_POST['name'] : '';
    $title= isset($_POST['title']) ? $_POST['title'] : '';
    $detail= isset($_POST['detail']) ? $_POST['detail'] : '';
    $category= isset($_POST['category']) ? $_POST['category'] : '';
    $status= isset($_POST['status']) ? $_POST['status'] : '';
    $post_time = date("F j, Y, G:i");
    if (!empty($_FILES["record_image"]['tmp_name'])) { //record_image
      $files = $_FILES["record_image"]['tmp_name'];
      $cloudUpload = \Cloudinary\Uploader::upload($files);
      $img_url = $cloudUpload['secure_url'];
           if(!empty($img_url)){
             update_field($_id,'img_url',$img_url);
             //$_SESSION['message']=$_SESSION['message']." บันทึกรูปภาพ ".$img_url." แล้ว/";
           }
         }// end if !empty _FILES

    $newData = json_encode(array(
      'date'=>$today,
      'name'=>$name,
      'title' => $title,
      'detail' => $detail,
      'category' => $category ,
      'post_time' => $post_time,
      'img_url' => $img_url,
      'status'=>'publish') );
      insert_post($newData);
      $_SESSION['message']=$_SESSION['message']." โพสต์เรียบร้อยแล้ว";
      show_all_post();
    break; // end case newrequest
  case 'comment' :
     $comment_no = isset($_POST['comment_no']) ? $_POST['comment_no'] : "";
     $user_comment_name = isset($_POST['user_comment_name']) ? $_POST['user_comment_name'] : "";
     $title = isset($_POST['title']) ? $_POST['title'] : "";
     $detail = isset($_POST['detail']) ? $_POST['detail'] : "";

          if (!empty($_FILES['record_image'])) { //record_image
            $files = $_FILES["record_image"]['tmp_name'];
            $cloudUpload = \Cloudinary\Uploader::upload($files);
            $img_url = $cloudUpload['secure_url'];
            if(!empty($img_url)){
              update_field($_id,'img_url',$img_url);
              //$_SESSION['message']=$_SESSION['message']." บันทึกรูปภาพ ".$img_url." แล้ว/";
            }
          }// end if !empty _FILES

     $comment_data = '{"'.$comment_no.'":{"user_comment_name":"'.$user_comment_name.'","title":"'.$title.'","detail":"'.$detail.'","img_url":"'.$img_url.'",}}';
     insert_post_comment($_id,$comment_data);
    $_SESSION['message']=$_SESSION['message']." แสดงความเห็นเรียบร้อยแล้ว";
    show_all_post();
    new_comment_form();
    break;
  default:
    show_all_post();
    new_comment_form();
    break;
}//end switch action

?>
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
              <h3 class="panel-title">รายการแจ้งคณะกรรมการรุ่น</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover table-striped">
                  <tbody>
                    <?php
        foreach($data as $rec){
          $i++;
          $_id=$rec->_id;
          foreach($_id as $rec_id){
          $_id=$rec_id;
          }
           $name=$rec->name;
           $title=$rec->title;
           $detail=$rec->detail;
           $category=$rec->category;
           $post_time=$rec->post_time;
           $status=$rec->status;
           $img_url=$rec->img_url;
           ?>
      <tr><td width='70%'><?php echo $title." : ".$post_time;?></td><td><?php echo $name;?></td></tr>
      <tr><td colspan="2">
        <table class="table table-sm table-hover table-striped">
          <tr><td><img src="<?php echo $img_url;?>" width="200"></td>
            <td><?php echo $detail;?></td></tr>
        </table></td></tr>
      <tr><td colspan="2">Read :  / Like : </td></tr>
           <?php    } //end foreach ?>
           </tbody>
         </table>
     </div><!-- class="table-responsive"> -->
     </div><!-- class="panel panel-success"> -->
           <?php
           }else{
           echo "ยังไม่ม POST ค่ะ";
               }
             }// end function show_all_post
               ?>

<?php
function new_post_form(){ ?>
  <?php $name = $_SESSION['user_info'];?>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
  <table class='table table-hover table-responsive table-bordered' width="300">
    <tr><td colspan="2">โพสต์ประชาสัมพันธ์ <input type='hidden' name='name' value='<?php echo $name;?>' class='form-control' /></td></tr>
      <td colspan="2">รายละเอียด<br>
          <textarea name="detail" rows="10" cols="30"class='form-control' /></textarea></td></tr>
        <tr><td colspan="2">แนบรูปภาพ<input type='file' name='record_image' class='form-control' /></td></tr>
      <tr><td>ผู้โพสต์ :</td><td>
        <?php $user_info = isset($_SESSION["user_info"]) ? $_SESSION['user_info'] : "";
        echo $user_info;?><input type="hidden"name="name" value="<?php echo $user_info;?>"></td></tr>
      <tr><td colspan="2" align="center"><input type="hidden"name="action" value="newpost">
              <input type="hidden"name="status" value="publish">
              <input type='submit' value='Save' class='btn btn-primary' /></td></tr>
      </table>
    </form>
<?php } // end request_form ?>

<?php
function review_post($_id){
  $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/post/'.$_id.'?apiKey='.MLAB_API_KEY);
  $data = json_decode($json);
  $isData=sizeof($data);
  if($isData >0){
    $i=0;
    $name=$data->name;
    $title=$data->title;
    $detail=$data->detail;
    $category=$data->category;
    $post_time=$data->post_time;
    $status=$data->status;
    $img_url=$data->img_url;
?>
    <div class="panel panel-success">
    <div class="panel-heading"><h3 class="panel-title"><?php echo $title;?></h3></div>
    <div class="table-responsive">
          <table class="table table-sm table-hover table-striped" width="300">
            <tr><td width='70%'><?php echo $title." : ".$post_time;?></td><td><?php echo $name;?></td></tr>
            <tr><td colspan="2">
              <table class="table table-sm table-hover table-striped">
                <tr><td><img src="<?php echo $img_url;?>" width="200"></td>
                  <td><?php echo $detail;?></td></tr>
              </table></td></tr>
            <tr><td colspan="2">Read :  / Like : </td></tr>
               </table>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
        <table class='table table-hover table-responsive table-bordered' width="300">
        <td colspan="2">ความเห็น<br>
                <textarea name="detail" rows="10" cols="30"class='form-control' /></textarea></td></tr>
              <tr><td colspan="2">รูปภาพ<input type='file' name='record_image' class='form-control' /></td></tr>
            <input type="hidden"name="name" value="<?php echo $user_info;?>"></td></tr>
            <tr><td colspan="2" align="center"><input type="hidden"name="action" value="newpost">
                    <input type="hidden"name="status" value="publish">
                    <input type='submit' value='แสดงความเห็น' class='btn btn-primary' /></td></tr>
            </table>
      </form>
               </div>
               </div>
                      <?php
                     }// end if >0
                }// end function review request
                ?>
<?php function insert_post($newData){
$opts = array('http' => array( 'method' => "POST",
                             'header' => "Content-type: application/json",
                             'content' => $newData
                                         )
                                      );
$url = 'https://api.mlab.com/api/1/databases/crma51/collections/post?apiKey='.MLAB_API_KEY.'';
      $context = stream_context_create($opts);
      $returnValue = file_get_contents($url,false,$context);
      return;
}//end function insert_request
 ?>
 <?php
 function insert_post_comment($_id,$comment_data);}{
   $comment_data = '{"'.$comment_no.'":{"user_comment_name":"'.$user_comment_name.'","title":"'.$title.'","detail":"'.$detail.'","img_url":"'.$img_url.'",}}';

         $newData = '{ "$set" : '.$comment_data.'}';
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
