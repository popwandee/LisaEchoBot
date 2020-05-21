<?php
// Initialize the session
session_start();
/*
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
*/
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
    <div class="page-header">
        <table><tr><td><h3>รบกวนตอบแบบฟอร์มเพื่อรวบรวมข้อมูลเพื่อน ๆ ล่าสุดครับ</h3></td></tr></table>
    </div>
    <?php if(isset($_SESSION["message"])){$message=$_SESSION['message'];echo $message;}else{$_SESSION['message']='';}?>
    <?php $message = isset($_GET['message']) ? $_GET['message'] : "";   echo $message; ?>
    <div class="container theme-showcase" role="main">
    <div class="jumbotron">
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>ยศ ชื่อ สกุล</td>
            <td>
              <select name="rank">
        <option value="<?php echo $rank;?>" selected><?php echo $rank;?></option>
        <option value="พ.ต.">พ.ต.</option>
        <option value="พ.ท.">พ.ท.</option>
        <option value="พ.อ.">พ.อ.</option>
        <option value="พล.ต.">พล.ต.</option>
        <option value="พล.ท.">พล.ท.</option>
        <option value="พล.อ.">พล.อ.</option>
        </select>
              <input type='text' name='name' value="<?php echo $name;?>" />
              <input type='text' name='lastname' value="<?php echo $lastname;?>" /></td>
        </tr>
        <tr>
            <td>ตำแหน่ง</td>
            <td><input type='text' name='position' value="<?php echo $position;?>" class='form-control' /></td>
            </tr>
        <tr>
            <td>จังหวัด</td>
            <td><select name="province">
      <option value="กรุงเทพมหานคร" selected>จังหวัดที่ปฏิบัติงาน</option>
      <option value="กรุงเทพมหานคร">กรุงเทพมหานคร</option>
      <option value="กระบี่">กระบี่ </option>
      <option value="กาญจนบุรี">กาญจนบุรี </option>
      <option value="กาฬสินธุ์">กาฬสินธุ์ </option>
      <option value="กำแพงเพชร">กำแพงเพชร </option>
      <option value="ขอนแก่น">ขอนแก่น</option>
      <option value="จันทบุรี">จันทบุรี</option>
      <option value="ฉะเชิงเทรา">ฉะเชิงเทรา </option>
      <option value="ชัยนาท">ชัยนาท </option>
      <option value="ชัยภูมิ">ชัยภูมิ </option>
      <option value="ชุมพร">ชุมพร </option>
      <option value="ชลบุรี">ชลบุรี </option>
      <option value="เชียงใหม่">เชียงใหม่ </option>
      <option value="เชียงราย">เชียงราย </option>
      <option value="ตรัง">ตรัง </option>
      <option value="ตราด">ตราด </option>
      <option value="ตาก">ตาก </option>
      <option value="นครนายก">นครนายก </option>
      <option value="นครปฐม">นครปฐม </option>
      <option value="นครพนม">นครพนม </option>
      <option value="นครราชสีมา">นครราชสีมา </option>
      <option value="นครศรีธรรมราช">นครศรีธรรมราช </option>
      <option value="นครสวรรค์">นครสวรรค์ </option>
      <option value="นราธิวาส">นราธิวาส </option>
      <option value="น่าน">น่าน </option>
      <option value="นนทบุรี">นนทบุรี </option>
      <option value="บึงกาฬ">บึงกาฬ</option>
      <option value="บุรีรัมย์">บุรีรัมย์</option>
      <option value="ประจวบคีรีขันธ์">ประจวบคีรีขันธ์ </option>
      <option value="ปทุมธานี">ปทุมธานี </option>
      <option value="ปราจีนบุรี">ปราจีนบุรี </option>
      <option value="ปัตตานี">ปัตตานี </option>
      <option value="พะเยา">พะเยา </option>
      <option value="พระนครศรีอยุธยา">พระนครศรีอยุธยา </option>
      <option value="พังงา">พังงา </option>
      <option value="พิจิตร">พิจิตร </option>
      <option value="พิษณุโลก">พิษณุโลก </option>
      <option value="เพชรบุรี">เพชรบุรี </option>
      <option value="เพชรบูรณ์">เพชรบูรณ์ </option>
      <option value="แพร่">แพร่ </option>
      <option value="พัทลุง">พัทลุง </option>
      <option value="ภูเก็ต">ภูเก็ต </option>
      <option value="มหาสารคาม">มหาสารคาม </option>
      <option value="มุกดาหาร">มุกดาหาร </option>
      <option value="แม่ฮ่องสอน">แม่ฮ่องสอน </option>
      <option value="ยโสธร">ยโสธร </option>
      <option value="ยะลา">ยะลา </option>
      <option value="ร้อยเอ็ด">ร้อยเอ็ด </option>
      <option value="ระนอง">ระนอง </option>
      <option value="ระยอง">ระยอง </option>
      <option value="ราชบุรี">ราชบุรี</option>
      <option value="ลพบุรี">ลพบุรี </option>
      <option value="ลำปาง">ลำปาง </option>
      <option value="ลำพูน">ลำพูน </option>
      <option value="เลย">เลย </option>
      <option value="ศรีสะเกษ">ศรีสะเกษ</option>
      <option value="สกลนคร">สกลนคร</option>
      <option value="สงขลา">สงขลา </option>
      <option value="สมุทรสาคร">สมุทรสาคร </option>
      <option value="สมุทรปราการ">สมุทรปราการ </option>
      <option value="สมุทรสงคราม">สมุทรสงคราม </option>
      <option value="สระแก้ว">สระแก้ว </option>
      <option value="สระบุรี">สระบุรี </option>
      <option value="สิงห์บุรี">สิงห์บุรี </option>
      <option value="สุโขทัย">สุโขทัย </option>
      <option value="สุพรรณบุรี">สุพรรณบุรี </option>
      <option value="สุราษฎร์ธานี">สุราษฎร์ธานี </option>
      <option value="สุรินทร์">สุรินทร์ </option>
      <option value="สตูล">สตูล </option>
      <option value="หนองคาย">หนองคาย </option>
      <option value="หนองบัวลำภู">หนองบัวลำภู </option>
      <option value="อำนาจเจริญ">อำนาจเจริญ </option>
      <option value="อุดรธานี">อุดรธานี </option>
      <option value="อุตรดิตถ์">อุตรดิตถ์ </option>
      <option value="อุทัยธานี">อุทัยธานี </option>
      <option value="อุบลราชธานี">อุบลราชธานี</option>
      <option value="อ่างทอง">อ่างทอง </option>
      <option value="อื่นๆ">อื่นๆ</option>
</select>
</td>
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
            <td>ข้อมูลเพิ่มเติม</td>
            <td><textarea name="comment" rows="10" cols="30"class='form-control' />รายละเอียดข้อมูลเพิ่มเติมค่ะ</textarea></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="hidden"name="formSubmit" value="true">
                <input type='submit' value='Save' class='btn btn-primary' />

            </td>
        </tr>
    </table>
</form>

</div><!-- jumbotron-->
</div><!-- container theme-showcase-->
<?php
$json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY);
$data = json_decode($json);
$isData=sizeof($data);
if($isData >0){
  $i=0;echo $i;echo "OK";
  ?>
  <table class='table table-hover table-responsive table-bordered'>
      <tr><td>ลำดับ</td><td>ยศ ชื่อ สกุล</td>
      <td>ตำแหน่ง</td>
      <td>จังหวัด</td>
      <td>Email</td>
      <td>LINE ID</td>
      <td>Telephone number</td></tr>
  <?php
  foreach($data as $rec){
    $i++;
     $rank=$rec->rank;
     $name=$rec->name;
     $lastname=$rec->lastname;
     $position=$rec->position;
     $province=$rec->province;
     $Email=$rec->Email;
     $Tel1=$rec->Tel1;
     $LineID=$rec->LineID;
     ?>
<tr><td><?php echo $i;?></td>
                 <td><?php echo $rank;?>
                   <?php echo $name;?>
                 <?php echo $lastname;?></td>
                 <td><?php echo $position;?></td>
                 <td><?php echo $province;?></td>
                 <td><?php echo $Email;?></td>
                 <td><?php echo $LineID;?></td>
                 <td><?php echo $Tel1;?></td>
             </tr>
     <?php    } //end foreach    ?>
       </table>
       <?php
}else{
echo "ยังไม่มีข้อมูลค่ะ";

    }

if(isset($_POST['formSubmit'])){

  $rank = isset($_POST['rank']) ? $_POST['rank'] : ""; echo $rank;
  $name = isset($_POST['name']) ? $_POST['name'] : ""; echo $name;
  $lastname = isset($_POST['lastname']) ? $_POST['lastname'] : ""; echo $lastname;
  $position = isset($_POST['position']) ? $_POST['position'] : ""; echo $position;
  $province = isset($_POST['province']) ? $_POST['province'] : ""; echo $province;
  $Email = isset($_POST['Email']) ? $_POST['Email'] : ""; echo $Email;
  $Tel1 = isset($_POST['Tel1']) ? $_POST['Tel1'] : ""; echo $Tel1;
  $LineID = isset($_POST['LineID']) ? $_POST['LineID'] : ""; echo $LineID;
  $comment = isset($_POST['comment']) ? $_POST['comment'] : ""; echo $comment;
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
    'status'=>'เพิ่มใหม่') );
  $opts = array('http' => array( 'method' => "POST",
                                 'header' => "Content-type: application/json",
                                 'content' => $newData
                                             )
                                          );
  $url = 'https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY;
  $context = stream_context_create($opts);
  $returnValue = file_get_contents($url,false,$context);
echo $newData;

          if($returnValue){
  		   $message= "<div align='center' class='alert alert-success'>เพิ่มข้อมูล ".$name." เรียบร้อย</div>";
  		   echo $message;

  	        }else{
  		   $message= "<div align='center' class='alert alert-danger'>ไม่สามารถบันทึกข้อมูลได้</div>";
  		echo $message;
                   }
  			$_SESSION["message"]=$message;
  		   	header("location: comment.php");
      			exit;
} //end if isset formSubmit
?>
 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
 <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

 <!-- Latest compiled and minified Bootstrap JavaScript -->
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
