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
    <div class="page-header">
        <table><tr><td><h1>ยินดีต้อนรับ <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. ครับ</h1></td></tr></table>
    </div>
    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger">ออกจากระบบ</a>
    </p>
 <?php



 if(empty($_POST['formSubmit'])&&(isset($_POST['id']))){ // มาจากหน้าอื่นๆ ไม่ได้คลิกยืนยันที่ฟอร์มแก้ไขข้อมูล
   // ดึงข้อมูลจากฐานข้อมูล
   $id=$_POST['id'];
   $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/crma51Phonebook?apiKey='.MLAB_API_KEY.'&q={"_id":{"$regex":"'.$id.'"}}');
   $data = json_decode($json);
   $isData=sizeof($data);
   if($isData >0){
      // มีข้อมูลผู้ใช้อยู่
   foreach($data as $rec){
      $rank=$rec->rank;
      $name=$rec->name;
      $lastname=$rec->lastname;
      $position=$rec->position;
      $Email=$rec->Email;
      $Tel1=>$rec->Tel1;
      $LineID=>$rec->LineID;
    } //end foreach
 }// end isData>0

 } // end if empty $formSubmit


?>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>

        <tr>
            <td>ชื่อ สมาชิกที่ต้องการให้แก้ไขข้อมูล</td>
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
        <tr>
            <td>LINE ID</td>
            <td><input type='text' name='LineID' value="<?php echo $LineID;?>" class='form-control' /></td>
            </tr>
        <tr>
            <td>Telephone number</td>
            <td><input type='text' name='Tel1' value="<?php echo $Tel1;?>" class='form-control' /></td>
            </tr>
        <tr>
            <td>ข้อมูลที่ต้องการแก้ไขเพิ่มเติม</td>
            <td><textarea name="comment" rows="10" cols="30"class='form-control' />ขอรายละเอียดข้อมูลที่ต้องการแก้ไขค่ะ</textarea></td>
        </tr>
        <tr>
            <td></td>
            <td>  <input type="hidden"name="id" value="<?php echo $id;?>">
                  <input type="hidden"name="formSubmit" value="true">
                <input type='submit' value='Save' class='btn btn-primary' />

            </td>
        </tr>
    </table>
</form>

</body>
</html>
