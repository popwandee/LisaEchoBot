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
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
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
if(isset($_POST['name'])&&(isset($_POST['_id']))){
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
            <td>
                <input type='submit' value='Save' class='btn btn-primary' />

            </td>
        </tr>
    </table>
</form>
  
</body>
</html>
