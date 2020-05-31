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
       <?php if(isset($_SESSION["message"])){
         $message=$_SESSION['message'];
         echo $message;
       }else{
         $_SESSION['message']='';
       }?>
      <div class="page-header">
          <table  class='table table-hover table-responsive table-bordered'>
            <tr><td><h3>รบกวนตอบแบบฟอร์มเพื่อรวบรวมข้อมูลเพื่อน ๆ ล่าสุดครับ แบบฟอร์มอยู่ด้านล่างนะครับ</h3></td></tr>
          </table>

      </div>
      <?php
      show_form();
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

          //$return = save_record_image($_FILES['record_image'],'');
          //$img_url=$return['data']['image']['url'];
          $cloudUpload = \Cloudinary\Uploader::upload($_FILES["record_image"]['tmp_name']);
          print_r($cloudUpload);

        }
      //insert_friend($rank,$name,$lastname,$position,$province,$Email,$Tel1,$LineID,$comment,$img_url);
      show_friend();
      }else{
        show_friend();
      }

 if(isset($_SESSION["message"])){
   $message=$_SESSION['message'];
   echo $message;
   $_SESSION['message']='';
 }

function show_friend(){
      $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY);
      $data = json_decode($json);
      $isData=sizeof($data);
      if($isData >0){
        $i=0;
        ?>
          <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title">เพื่อนที่กรอกข้อมูลแล้ว</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover table-striped">
                  <thead>
                    <tr>
                      <th>ลำดับ</th><th>ยศ ชื่อ สกุล</th>
            <th>ตำแหน่ง</th>
            <th>จังหวัด</th>
            <th>Email</th>
            <th>LINE ID</th>
            <th>Telephone number</th>
          <th>Action</th></tr>
          </thead><tbody>
        <?php
        foreach($data as $rec){
          $i++;
          $_id=$rec->_id;
          foreach($_id as $rec_id){
          $_id=$rec_id;
          }
           $rank=$rec->rank;
           $name=$rec->name;
           $lastname=$rec->lastname;
           $position=$rec->position;
           $province=$rec->province;
           $Email=$rec->Email;
           $Tel1=$rec->Tel1;
           $LineID=$rec->LineID;
           //$file_url=$rec->file_url;
           ?>
      <tr><td><?php echo $i;?></td>
                       <td class="text-nowrap"><a href='friend_preview.php?_id=<?php echo $_id;?>'><?php echo $rank;?>
                         <?php echo " ".$name;?>
                       <?php echo " ".$lastname;?></a></td>
                       <td><?php echo $position;?></td>
                       <td><?php echo $province;?></td>
                       <td><?php echo $Email;?></td>
                       <td><?php echo $LineID;?></td>
                       <td><?php echo $Tel1;?></td>
                       <td><a href='comment.php?id=<?php echo $_id;?>'>แจ้งแก้ไข</a></td>
                   </tr>
           <?php    } //end foreach
             ?>
           </tbody>
         </table>
     </div><!-- class="table-responsive"> -->
     </div><!-- class="panel panel-success"> -->
           <?php
           }else{
           echo "ยังไม่มีข้อมูลค่ะ";
               }
             }// end function show_friend
               ?>
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
            <td><input type='text' name='Email' class='form-control' /></td>
            </tr>
        <tr>
        <tr>
            <td>LINE ID</td>
            <td><input type='text' name='LineID'  class='form-control' /></td>
            </tr>
        <tr>
            <td>โทรศัพท์</td>
            <td><input type='text' name='Tel1'  class='form-control' /></td>
            </tr>
        <tr>
            <td>ข้อมูลเพิ่มเติม</td>
            <td><textarea name="comment" rows="10" cols="30"class='form-control' />รายละเอียดข้อมูลเพิ่มเติมค่ะ</textarea></td>
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
            $_SESSION['message']='=> เพิ่มข้อมูลสำเร็จ.';
            }else{
            $_SESSION['message']='=> เพิ่มข้อมูลไม่สำเร็จ.';
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
