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
    <div class="jumbotron">
          <?php show_all_comment();?>
    <span class="label label-primary">แจ้งแก้ไขข้อมูล</span>
    </div>
    <div class="jumbotron">
 <?php
 if(!isset($_POST['formSubmit'])){ // มาจากหน้าอื่นๆ ไม่ได้คลิกยืนยันที่ฟอร์มแก้ไขข้อมูล
   // ดึงข้อมูลจากฐานข้อมูล
   $id=$_GET['id'];//echo "GET ID: ".$id;
   $url="https://api.mlab.com/api/1/databases/crma51/collections/friend/".$id."?apiKey=".MLAB_API_KEY;
   $json = file_get_contents($url);//echo "\nurl is =>".$url;
   $data = json_decode($json);//echo "\n data is =>";print_r($data);
   $isData=sizeof($data);
   $i=0;
 if($isData >0){
      // มีข้อมูลผู้ใช้อยู่
      $rank=$data->rank;
      $name=$data->name;
      $lastname=$data->lastname;
      $position=$data->position;
      $province=$data->province;
      $Email=$data->Email;
      $Tel1=$data->Tel1;
      $LineID=$data->LineID;
 }// end isData>0
 else{
   echo "NO data";
 }
?>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>แก้ไขข้อมูล</td>
            <td>
              <select name="rank">
        <option value="<?php echo $rank;?>" selected><?php echo $rank;?></option>
        <option value="ร.ต.">ร.ต.</option>
        <option value="ร.ท.">ร.ท.</option>
        <option value="ร.อ.">ร.อ.</option>
        <option value="พ.ต.">พ.ต.</option>
        <option value="พ.ท.">พ.ท.</option>
        <option value="พ.อ.">พ.อ.</option>
        <option value="พล.ต.">พล.ต.</option>
        <option value="พล.ท.">พล.ท.</option>
        <option value="พล.อ.">พล.อ.</option>
        </select>
              <input type='text' name='name' value="<?php echo $name;?>" />
              <input type='text' name='lastname' value="<?php echo $lastname;?>"  /></td>
        </tr>
        <tr>
            <td>ตำแหน่ง</td>
            <td><input type='text' name='position' value="<?php echo $position;?>" class='form-control' /></td>
            </tr>
        <tr>
            <td>จังหวัด</td>
            <td><select name="province">
      <option value="<?php echo $province;?>" selected><?php echo $province;?></option>
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
            <td>โทรศัพท์</td>
            <td><input type='text' name='Tel1' value="<?php echo $Tel1;?>" class='form-control' /></td>
            </tr>
        <tr>
            <td>แจ้งรายละเอียดข้อมูลที่ต้องการแก้ไข</td>
            <td><textarea name="comment" rows="10" cols="30"class='form-control' />ระบุรายละเอียดข้อมูลที่ต้องการแก้ไขด้วยค่ะ</textarea></td>
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
<?php  } // end if empty $formSubmit
else{ // set formSubmit from form
  // นำข้อมูลเข้าเก็บในฐานข้อมูล
  if(isset($_POST['id'])){$id=$_POST['id'];}else{$id=''; }
  if(isset($_POST['rank'])){$rank=$_POST['rank'];}else{$rank=''; }
  if(isset($_POST['name'])){$name=$_POST['name'];}else{$name=''; }
  if(isset($_POST['lastname'])){$lastname=$_POST['lastname'];}else{$lastname=''; }
  if(isset($_POST['postition'])){$position=$_POST['position'];}else{$position=''; }
  if(isset($_POST['province'])){$province=$_POST['province'];}else{$province=''; }
  if(isset($_POST['Email'])){$Email=$_POST['Email'];}else{$Email=''; }
  if(isset($_POST['Tel1'])){$Tel1=$_POST['Tel1'];}else{$Tel1=''; }
  if(isset($_POST['LineID'])){$LineID=$_POST['LineID'];}else{$LineID=''; }
  if(isset($_POST['comment'])){$comment=$_POST['comment'];}else{$comment=''; }
  $newData = json_encode(array(
    'userid' => $id,
    'name'=>$rank.' '.$name.' '.$lastname,
    'content' => 'position: '.$postion.' province: '.$province.' Email: '.$Email.' Telephone: '.$Tel1.' LineID: '.$LineID ,
  	'comment' => $comment,
    'status'=>0 );
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
  		   	header("location: friend.php");
      			exit;


} ?>
</div><!-- jumbotron-->
</div><!-- container theme-showcase-->
<?php
function show_all_comment(){
      $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/comment?apiKey='.MLAB_API_KEY);
      $data = json_decode($json);
      $isData=sizeof($data);
      if($isData >0){
        $i=0;
        ?>
          <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title">รายการแจ้งแก้ไขข้อมูล</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover table-striped">
                  <thead>
                    <tr>
                      <th>ลำดับ</th><th>ยศ ชื่อ สกุล</th>
            <th>ข้อมูลที่แจ้งแก้ไข</th>
          <th>การดำเนินการ</th></tr>
          </thead><tbody>
        <?php
        foreach($data as $rec){
          $i++;
          $_id=$rec->_id;
           $name=$rec->name;
           $comment=$rec->comment;
           $status=$rec->status;
           ?>
      <tr><td><?php echo $i;?></td>
                       <td class="text-nowrap"><?php echo $name;?></td>
                       <td><?php echo $comment;?></td>
                       <td><?php if($status){echo "ดำเนินการแก้ไขแล้ว";}else{echo "อยู่ระหว่างดำเนินการ";}?></td>
                  </tr>
           <?php    } //end foreach
             ?>
           </tbody>
         </table>
     </div><!-- class="table-responsive"> -->
     </div><!-- class="panel panel-success"> -->
           <?php
           }else{
           echo "ยังไม่มีข้อมูลแจ้งแก้ไขค่ะ";
               }
             }// end function show_friend
               ?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
