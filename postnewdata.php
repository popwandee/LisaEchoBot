<?php
// Initialize the session
session_start();

$message = isset($_SESSION['message'])? $_SESSION['message']: "";

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
</head>
<body>

    <?php
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
            <p>แจ้งแก้ไข/เพิ่มข้อมูล</p>
<?php // core logic
echo $message;
if($action=='newpost') {
    echo "insert new post to db";
  /*
    $name= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
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
                 $option=array("folder" => "post","public_id" => $public_id);
               $file_name = $img_url ="post/$public_id.".$imageFileType;
               $img_index = 'img_url-'.$index;
               $newData->$img_index=$file_name;
               $cloudUpload = \Cloudinary\Uploader::upload($files,$option);
               $index++;
             }

           }

         }// end if !empty _FILES

      insert_post($newData);
      */
      //show_all_post();
  }elseif($action=='showupdateform'){
      //update_form();
      echo "Show Update Form";
  }elseif($action=='shownewpostform'){
      //new_post_form();
      echo "Show new post form";
  }


?>

</div><!-- jumbotron-->
</div><!-- container theme-showcase-->

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
 function update_form($_id){ ?>

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


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
