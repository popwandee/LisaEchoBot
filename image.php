<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

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
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <h1>AFAPS40 - CRMA51</h1>
      <p>เว็บไซต์ เตรียมทหาร รุ่นที่ 40 จปร.รุ่นที่ 51</p>
    </div>
  </div>
   <div class="container">

         <div class="col-sm-4">
           <div class="panel panel-warning">
             <div class="panel-heading">
               <h3 class="panel-title">ประชาสัมพันธ์</h3>
             </div>
             <div class="panel-body">
               <a href='imagepreview.php?imageid=1.jpg?alt=media&token=e9d388df-0b6d-4e10-a49c-6472f9aff38a'><img src='https://firebasestorage.googleapis.com/v0/b/lisa-77436.appspot.com/o/image%2F1.jpg?alt=media&token=e9d388df-0b6d-4e10-a49c-6472f9aff38a' width='100'></a>
               <a href='imagepreview.php?imageid=95736235_157455389101365_4497114901063401472_o.jpg?alt=media&token=32f10846-4da0-4db3-b25f-250938cbc9fb'><img src='https://firebasestorage.googleapis.com/v0/b/lisa-77436.appspot.com/o/image%2F95736235_157455389101365_4497114901063401472_o.jpg?alt=media&token=32f10846-4da0-4db3-b25f-250938cbc9fb'width='100'></a>
             </div>
           </div>
           <div class="panel panel-danger">
             <div class="panel-heading">
               <h3 class="panel-title">ข้อมูลสมาชิก</h3>
             </div>
             <div class="panel-body">
               <a href='https://firebasestorage.googleapis.com/v0/b/lisa-77436.appspot.com/o/image%2F11.jpg?alt=media&token=dc346d7b-8d66-4861-a2b8-f419f76b471c'><img src='https://firebasestorage.googleapis.com/v0/b/lisa-77436.appspot.com/o/image%2F11.jpg?alt=media&token=dc346d7b-8d66-4861-a2b8-f419f76b471c' width='100'>
             </div>
           </div>
         </div><!-- /.col-sm-4 -->
    </div> <!-- end .container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
