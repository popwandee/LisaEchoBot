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
       <?php if(isset($_SESSION["message"])){
         $message=$_SESSION['message'];

       }else{
         $_SESSION['message']='';
       }?>
      <div class="page-header">
          <table  class='table table-hover table-responsive table-bordered'>
            <tr><td><h3>รบกวนตอบแบบฟอร์มเพื่อรวบรวมข้อมูลเพื่อน ๆ ล่าสุดครับ แบบฟอร์มอยู่ด้านล่างนะครับ</h3></td></tr>
          </table>

      </div>
      <?php

      if(isset($_POST['formSubmit'])){
        $rank = isset($_POST['rank']) ? $_POST['rank'] : "";
        $name = isset($_POST['name']) ? $_POST['name'] : "";
        $lastname = isset($_POST['lastname']) ? $_POST['lastname'] : "";
        $position = isset($_POST['position']) ? $_POST['position'] : "";
        $province = isset($_POST['province']) ? $_POST['province'] : "";
        $Email = isset($_POST['Email']) ? $_POST['Email'] : "";
        $Tel1 = isset($_POST['Tel1']) ? $_POST['Tel1'] : "";
        $LineID = isset($_POST['LineID']) ? $_POST['LineID'] : "";
        $comment = isset($_POST['comment']) ? $_POST['comment'] : "";

        if (!empty($_FILES['record_image'])) { //record_image
          $files = $_FILES["record_image"]['tmp_name'];
          $option= array("public_id" => "$Tel1");
          $cloudUpload = \Cloudinary\Uploader::upload($files,$option);
          $img_url = $cloudUpload['secure_url'];

        }
      $result=insert_friend($rank,$name,$lastname,$position,$province,$Email,$Tel1,$LineID,$comment,$img_url);
      echo $message; $_SESSION['message']='';
      echo $result;
      }else{
      show_form();
      }

 if(isset($_SESSION["message"])){
   $message=$_SESSION['message'];
   echo $message;
   $_SESSION['message']='';
 }

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
<?php function show_form(){ ?>
        <div class="panel panel-success">
          <div class="panel-heading">
            <h3 class="panel-title">แบบฟอร์มบันทึกข้อมูล จปร.51</h3>
            <div class="alert alert-warning" role="alert">ช่วงนี้เปิดให้กรอกข้อมูลโดยไม่ต้อง ล็อกอินเข้าใช้งานเพื่อให้สะดวกนะครับ
              หลังวันที่ 31 พ.ค.63 จะต้องลงทะเบียน ล็อกอิน จึงจะสามารถเข้าดูข้อมูลของเพื่อนๆ ได้ เพื่อเป็นการรักษาความปลอดภัยข้อมูลเพื่อน ๆ ครับ
            </div>
          </div>
          <div class="panel-body">
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <table  class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>ยศ ชื่อ สกุล</td>
            <td>
              <select name="rank">
        <option value="ร.ต.">ร.ต.</option>
        <option value="ร.ท.">ร.ท.</option>
        <option value="ร.อ.">ร.อ.</option>
        <option value="พ.ต.">พ.ต.</option>
        <option value="พ.ท." selected>พ.ท.</option>
        <option value="พ.อ.">พ.อ.</option>
        <option value="พล.ต.">พล.ต.</option>
        <option value="พล.ท.">พล.ท.</option>
        <option value="พล.อ.">พล.อ.</option>
        </select>
              <input type='text' name='name'  />
              <input type='text' name='lastname' /></td>
        </tr>
        <tr>
            <td>ตำแหน่ง (ตัวอักษรย่อ)</td>
            <td><input type='text' name='position'  class='form-control' /></td>
            </tr>
        <tr>
            <td>จังหวัด (ที่ปฏิบัติงาน)</td>
            <td><?php select_province();?>
</td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input type='text' name='Email' class='form-control' /></td>
            </tr>
        <tr>
        <tr>
            <td>LINE ID</td>
            <td><input type='text' name='LineID'  class='form-control' /></td>
            </tr>
        <tr>
            <td>โทรศัพท์</td>
            <td><input type='text' name='Tel1'  class='form-control' required='' /></td>
            </tr>
        <tr>
            <td colspan="2">ข้อมูลเพิ่มเติม - รายละเอียดข้อมูลเพิ่มเติมค่ะ เช่นมีหมายเลขโทรศัพท์มากกว่า 1 หมายเลข หมายเลขหลักให้ระบุในช่องโทรศัพท์ เพื่อใช้ล็อกอินเข้าระบบ สำหรับหมายเลขเพิ่มเติมให้แจ้งตรงนี้นะคะ<br>
            <textarea name="comment" rows="10" cols="30"class='form-control' /></textarea></td>
        </tr>
        <tr>
          <td>รูปภาพ</td>
          <td>	<input type="file" name="record_image" class="form-control" accept="image/*"></td>
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

function insert_friend($rank,$name,$lastname,$position,$province,$Email,$Tel1,$LineID,$comment,$img_url){

  $newData = json_encode(array(
  	'rank' => $rank,
    'name'=>$name,
    'lastname' =>$lastname,
    'position' => $position,
    'province' =>$province,
    'Email'=> $Email,
    'Tel1'=>$Tel1,
    'LineID' =>$LineID ,
  	'comment' => $comment,
  	'img_url' => $img_url,
    'password' =>'$2y$10$iY75qUiPFNQBrpzZsd8ybe2yijNigzOsFAMeNtkqDxGSqP22UkzGu',
    'type'=> 'สมาชิก',
    'approved'=> 0,
    'status'=>'เพิ่มใหม่') );
  $opts = array('http' => array( 'method' => "POST",
                                 'header' => "Content-type: application/json",
                                 'content' => $newData
                                             )
                                          );
  $url = 'https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY;
  $context = stream_context_create($opts);
  $returnValue = file_get_contents($url,false,$context);
          if($returnValue){
            $message=$_SESSION['message']='=> เพิ่มข้อมูลสำเร็จ. ขอบคุณค่ะ ผู้ดูแลระบบจะพิจารณาอนุมัติรายชื่อ หลังจากนั้นคุณสามารถเข้าระบบได้ด้วยหมายเลขโทรศัพท์ และรหัสผ่านที่ผู้ดูแลระบบจะจัดส่งให้นะคะ';
            return $message;
            }else{
            $message=$_SESSION['message']='=> เพิ่มข้อมูลไม่สำเร็จ.';
            return $message;
           }
} //function insert_friend
?>

</div><!-- jumbotron-->
</div><!-- container theme-showcase-->
 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
 <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

 <!-- Latest compiled and minified Bootstrap JavaScript -->
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
