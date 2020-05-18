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
if(isset($_POST['name'])&&(isset($_POST['_id']))&&(isset($_POST['comment']))){
	// รับค่าข้อมูลจาก POST ให้ตัวแปร
 $name =	htmlspecialchars(strip_tags($_POST['name']));
 $_id=htmlspecialchars(strip_tags($_POST['_id']));
 $comment =	htmlspecialchars(strip_tags($_POST['comment']));

// นำข้อมูลเข้าเก็บในฐานข้อมูล
$newData = json_encode(array('_id' => $_id,
			     'name' => $name,
			     'comment' => $comment) );
$opts = array('http' => array( 'method' => "POST",
                               'header' => "Content-type: application/json",
                               'content' => $newData
                                           )
                                        );
$url = 'https://api.mlab.com/api/1/databases/crma51/collections/comment?apiKey='.MLAB_API_KEY.'';
        $context = stream_context_create($opts);
        $returnValue = file_get_contents($url,false,$context);

        if($returnValue){
		   $message= "<div align='center' class='alert alert-success'>รับแจ้งแก้ไขข้อมูล ".$name." เรียบร้อย</div>";
		   echo $message;

	        }else{
		   $message= "<div align='center' class='alert alert-danger'>ไม่สามารถบันทึกรับแจ้งการแก้ไขข้อมูลได้</div>";
		echo $message;
                 }
			$_SESSION["message"]=$message;
		   	header("location: search.php");
    			exit;
        // ยังไม่มีการโพสต์ข้อมูลจากแบบฟอร์ม
    }else{
        echo "<div align='center' class='alert alert-success'>".$dateTimeToday."</div>";

}  // end of if(isset($_POST['_id'])&&isset($_POST['name']))

if(isset($_POST['name'])){
	$name=$_POST['name'];
}else{
$name='';
}
?>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>

        <tr>
            <td>ชื่อ สมาชิกที่ต้องการให้แก้ไขข้อมูล</td>
            <td><input type='text' name='name' value="<?php echo $name;?>" class='form-control' /></td>
        </tr>
        <tr>
            <td>ข้อมูลที่ต้องการแก้ไข</td>
            <td><textarea name="comment" rows="10" cols="30"class='form-control' />ข้อมูลที่ต้องการแก้ไข</textarea></td>
        </tr>
        <tr>
            <td></td>
            <td>  <input type="hidden"name="_id" value="<?php echo $_id;?>">
                <input type='submit' value='Save' class='btn btn-primary' />

            </td>
        </tr>
    </table>
</form>

</body>
</html>
