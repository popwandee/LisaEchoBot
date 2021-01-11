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
/*
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
*/

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
<script data-ad-client="ca-pub-0730772505870150" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</head>
<body>
    <?php include 'navigation.html';?>


    <div class="container theme-showcase" role="main">
    <div class="jumbotron">
      <div class="page-header">
          <table  class='table table-hover table-responsive table-bordered'>
            <tr><td><h3>ส่งข้อความครับ</h3></td></tr>
          </table>

      </div>
      <?php

      if(isset($_POST['formSubmit'])){
        $log = $comment = isset($_POST['comment']) ? $_POST['comment'] : "";
        $newlog = $dateTimeNow = $datetime->format('YmdHms');
        $log = $log.$newlog;

        $newlog = $message = "ทดสอบฟังก์ชั่นใหม่ ".$comment."\nเวลา ".$dateTimeNow."\nขอบคุณค่ะ";
        $log = $log."<br>centent ".$newlog;
        sendlinemesg();
        header('Content-Type: text/html; charset=utf8');
        $newlog = $res = notify_message($message);
        $log = $log."<br>result send notify ".$newlog;
        //echo "<script>alert('เพิ่มข้อมูลเรียบร้อย');</script>";

    }

    show_form();

?>
</div>
</div>
<?php echo $log;?>
<?php function show_form(){ ?>
        <div class="panel panel-success">
          <div class="panel-heading">
            <h3 class="panel-title">Line Notify</h3>
          </div>
          <div class="panel-body">
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <table  class='table table-hover table-responsive table-bordered'>

        <tr>
            <td colspan="2">ข้อมูลเพิ่มเติม<br>
            <textarea name="comment" rows="10" cols="30"class='form-control' /></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="hidden"name="formSubmit" value="true">
                <input type='submit' value='Save' class='btn btn-primary' />
            </td>
        </tr>

    </table>
</form>
</div class="panel-body">
</div class="panel panel-success">
<?php } // end show_form ?>


<?php
function sendlinemesg(){
   define('LINE_API',"https://notify-api.line.me/api/notify");
   define('LINE_TOKEN',"");
   echo "<br>API is ";echo LINE_API;echo "<br>TOKEN is";echo LINE_TOKEN;
   function notify_message($message){
     $queryData = array("message"=>$message);
     $queryData = http_build_query($queryData,'','&');
     $headerOptions = array(
       'http'=> array(
         'method' => 'POST',
         'header' => "Content-Type: application/x-www-form-urlencoded\r\n"
                      ."Authorization : Bearer ".LINE_TOKEN."\r\n"
                      ."Content-Length : ".strlen($queryData)."\r\n",
          'content' => $queryData
       )
     );
     $context = stream_context_create($headerOptions);echo "<br>Context is ".$context;
     $result = file_get_contents(LINE_API,FALSE,$context);
     $res = json_decode($result);
     echo "<br>Query Data is ".$queryData;
     echo "<br>LINE_NOTIFY is ".$message;
     echo "<br>Result is ".$res;
     return $res;
   }
}
?>
</div><!-- jumbotron-->
</div><!-- container theme-showcase-->
 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
 <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

 <!-- Latest compiled and minified Bootstrap JavaScript -->
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
