<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
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

  <div class="container theme-showcase" role="main">
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <h1>ลงทะเบียนเพิ่มสมาชิก</h1>
      <p>เว็บไซต์ เตรียมทหาร รุ่นที่ 40 จปร.รุ่นที่ 51</p>
    </div>

    <div class="page-header">
      <div class="panel panel-info">
	    <?php	    $rank='';	    $name='';	    $lastname=''; $position=''; $Email=''; $Tel1=''; $LineID='';
// กรณีได้รับข้อมูลมาแล้ว
if(isset($_POST['rank'])&&(isset($_POST['name']))&&(isset($_POST['lastname']))){
	// รับค่าข้อมูลจาก POST ให้ตัวแปร
 $rank =	htmlspecialchars(strip_tags($_POST['rank']));
 $name =	htmlspecialchars(strip_tags($_POST['name']));
 $lastname=	htmlspecialchars(strip_tags($_POST['lastname']));
 $position =	htmlspecialchars(strip_tags($_POST['position']));
 $Email =	htmlspecialchars(strip_tags($_POST['Email']));
 $Tel1 =	htmlspecialchars(strip_tags($_POST['Tel1']));
 $LineID =	htmlspecialchars(strip_tags($_POST['LineID']));

// นำข้อมูลเข้าเก็บในฐานข้อมูล
$newData = json_encode(array('rank' => $rank,
			     'name' => $name,
			     'lastname' => $lastname,
			     'position' => $position,
			    'Email'	=> $Email,
			    'Tel1'	=> $Tel1,
			    'LineID' => $LineID) );
$opts = array('http' => array( 'method' => "POST",
                               'header' => "Content-type: application/json",
                               'content' => $newData
                                           )
                                        );
$url = 'https://api.mlab.com/api/1/databases/crma51/collections/crma51Phonebook?apiKey='.MLAB_API_KEY.'';
        $context = stream_context_create($opts);
        $returnValue = file_get_contents($url,false,$context);

        if($returnValue){
		   $message= "<div align='center' class='alert alert-success'>บันทึกข้อมูล ".$rank.$name." ตำแหน่ง ".$position." เรียบร้อย</div>";
		   echo $message;

	        }else{
		   $message= "<div align='center' class='alert alert-danger'>ไม่สามารถบันทึกได้</div>";
		echo $message;
                 }
			$_SESSION["message"]=$message;
		   	header("location: search.php");
    			exit;
        // ยังไม่มีการโพสต์ข้อมูลจากแบบฟอร์ม
    }

?>
<div class="row">
  <div class="col-sm-4" >
<div class="panel-body">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>

        <tr>
            <td>ยศ ชื่อ สกุล</td>
            <td><input type='text' name='rank' value="<?php echo $rank;?>" class='form-control' />
            <input type='text' name='name' value="<?php echo $name;?>" class='form-control' />
            <input type='text' name='lastname' value="<?php echo $lastname;?>" class='form-control' /></td>
        </tr>
        <tr>
            <td>ตำแหน่ง</td>
            <td><input type='text' name='position' value="<?php echo $position;?>" class='form-control' /></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input type='text' name='Email' value="<?php echo $Email;?>" class='form-control' /></td>
        </tr>
        <tr>
            <td>ตำแหน่ง</td>
            <td><input type='text' name='Tel1' value="<?php echo $Tel1;?>" class='form-control' /></td>
        </tr>
        <tr>
            <td>ID LINE</td>
            <td><input type='text' name='LineID' value="<?php echo $LineID;?>" class='form-control' /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' value='Save' class='btn btn-primary' />


            </td>
        </tr>
    </table>
</form>
</div> <!-- panel-body -->
</div> <!-- panel panel-primary-->
</div> <!-- col-sm-4 -->
</div> <!-- row -->

</div> <!-- panel panel-info -->
  </div> <!-- page-header -->
</div> <!-- container theme-showcase -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
