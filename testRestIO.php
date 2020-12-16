<?php
// Initialize the session
session_start();
/*
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
// ตรวจสอบ ประเภทสมาชิก
if(isset($_SESSION['type'])){    $user_type = $_SESSION['type'];
}else{                           $user_type = "";
}
*/

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
            <h1>Knowledge Management</h1>
            <p>บริหารจัดการองค์ความรู้</p>

<?php // core logic
$result=https://mydb-fafc.restdb.io/rest/people?q={"name": "ไพศาล"};
print_r($result);

?>

</div><!-- jumbotron-->
</div><!-- container theme-showcase-->


</body>
</html>
