<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
// ตรวจสอบ ประเภทสมาชิก
if(isset($_SESSION['type'])){    $user_type = $_SESSION['type'];
}else{                           $user_type = "";
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
    <?php
    // ตรวจสอบ $_id จาก _GET และ _POST
          if(isset($_GET['_id'])){         $_id = $_GET['_id'];
          }elseif(isset($_POST['_id'])){   $_id = $_POST['_id'];
          }else{                           $_id = "";
          }

  //      ตรวจสอบ Action จาก _GET หรือ _POST
          if(isset($_GET['action'])){         $action = $_GET['action'];
          }elseif(isset($_POST['action'])){   $action = $_POST['action'];
          }else{                              $action = "";
          }
          ?>
          <div class="container theme-showcase" role="main">
          <div class="jumbotron">
            <h1>AFAPS40 - CRMA51</h1>
            <p>เตรียมทหาร รุ่นที่ 40 จปร.รุ่นที่ 51</p>
                 <?php $message=isset($_SESSION['message']) ? $_SESSION['message'] : '';
                       echo $message;$_SESSION['message']='';?>

<?php // core logic

switch ($action) {
  case 'review':
    //echo "action is review and not empty _id is $_id, call function review_request";
    review_request($_id);
    show_all_request();
    break;
  case 'newrequest' :
    $name= isset($_POST['name']) ? $_POST['name'] : '';
    $title= isset($_POST['title']) ? $_POST['title'] : '';
    $detail= isset($_POST['detail']) ? $_POST['detail'] : '';
    $type= isset($_POST['type']) ? $_POST['type'] : '';
    $urgent= isset($_POST['urgent']) ? $_POST['urgent'] : '';
    $status= isset($_POST['status']) ? $_POST['status'] : '';
    $today = date("F j, Y, G:i");
         if (!empty($_FILES['record_image'])) { //record_image
           $return = save_record_image($_FILES['record_image'],'');
           $img_url=$return['data']['image']['url'];
           if(!empty($img_url)){
             update_field($_id,'img_url',$img_url);
             //$_SESSION['message']=$_SESSION['message']." บันทึกรูปภาพ ".$img_url." แล้ว/";
           }
         }// end if !empty _FILES

    $newData = json_encode(array(
      'date'=>$today,
      'name'=>$name,
      'title' => $title,
      'detail' => $detail,
      'type' => $type ,
      'urgent' => $urgent,
      'img_url' => $img_url,
      'status'=>'แจ้งใหม่') );
      insert_request($newData);
      $_SESSION['message']=$_SESSION['message']." เพิ่มเรื่องแจ้งคณะกรรมการรุ่นเรียบร้อยแล้ว";
      show_all_request();
    break; // end case newrequest
  case 'edited' :
    $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/request/'.$_id.'?apiKey='.MLAB_API_KEY);
    $data = json_decode($json);
    $isData=sizeof($data);
    if($isData >0){

     $name_db=$data->name;$update_name = isset($_POST['name']) ? $_POST['name'] : "";
     if($update_name!=$name_db){update_field($_id,'name',$update_name);}

     $title_db=$data->title;$update_title = isset($_POST['title']) ? $_POST['title'] : "";
     if($update_title!=$title_db){update_field($_id,'title',$update_title);}

     $detail_db=$data->detail;$update_detail = isset($_POST['detail']) ? $_POST['detail'] : "";
     if($update_detail!=$detail_db){update_field($_id,'detail',$update_detail);}

     $type_db=$data->type;$update_type = isset($_POST['type']) ? $_POST['type'] : "";
     if($update_type!=$type_db){update_field($_id,'type',$update_type);}

     $urgent_db=$data->urgent;$update_urgent = isset($_POST['urgent']) ? $_POST['urgent'] : "";
     if($update_urgent!=$urgent_db){update_field($_id,'urgent',$update_urgent);}

     $status_db=$data->status;$update_status = isset($_POST['status']) ? $_POST['status'] : "";
     if($update_status!=$status_db){update_field($_id,'status',$update_status);}

     if (!empty($_FILES['record_image'])) { //record_image
       $files = $_FILES["record_image"]['tmp_name'];
       $cloudUpload = \Cloudinary\Uploader::upload($files);
       $img_url = $cloudUpload['secure_url'];
       if(!empty($img_url)){
         update_field($_id,'img_url',$img_url);
         //$_SESSION['message']=$_SESSION['message']." บันทึกรูปภาพ ".$img_url." แล้ว/";
       }
     }// end if !empty _FILES

    }// end if data>0
    $_SESSION['message']=$_SESSION['message']." แก้ไข/ปรับปรุงเรื่องแจ้งคณะกรรมการรุ่นเรียบร้อยแล้ว";
    show_all_request();
    new_request_form();
    break;
  default:
    show_all_request();
    new_request_form();
    break;
}//end switch action

?>
</div><!-- jumbotron-->
</div><!-- container theme-showcase-->

<?php
function show_all_request(){
      $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/request?apiKey='.MLAB_API_KEY);
      $data = json_decode($json);
      $isData=sizeof($data);
      if($isData >0){
        $i=0;
        ?>
          <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title">รายการแจ้งคณะกรรมการรุ่น</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover table-striped">
                  <thead>
                    <tr>
                      <th>ลำดับ</th><th>เรื่อง</th>
            <th>ผู้แจ้ง</th>
          <th>ความเร่งด่วน</th>
        <th>สถานะ</th>
      <th>ดำเนินการ</th></tr>
          </thead><tbody>
        <?php
        foreach($data as $rec){
          $i++;
          $_id=$rec->_id;
          foreach($_id as $rec_id){
          $_id=$rec_id;
          }
           $name=$rec->name;
           $title=$rec->title;
           $detail=$rec->detail;
           $type=$rec->type;
           $urgent=$rec->urgent;
           $status=$rec->status;
           ?>
      <tr><td><?php echo $i;?></td>
      <td class="text-nowrap"><a href="request.php?action=review&_id=<?php echo $_id;?> "><?php echo $title;?></a></td>
      <td class="text-nowrap"><?php echo $name;?></td>
      <td><?php echo $urgent;?></td>
      <td><?php echo $status;?></td>
      <td><a href="request.php?action=review&_id=<?php echo $_id;?> "> ดูรายละเอียด </a></td>
      </tr>
           <?php    } //end foreach ?>
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

<?php
function new_request_form(){ ?>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
  <table class='table table-hover table-responsive table-bordered' width="300">
    <tr><td colspan="2">แจ้งเรื่องต่าง ๆ ให้คณะกรรมการรุ่นทราบ</td></tr>
    <tr><td>ความเร่งด่วน <select name="urgent" class='form-control' >
            <option value="เร่งด่วน">เร่งด่วน</option>
            <option value="ไม่ด่วน">ไม่ด่วน</option>
          </select></td>
        <td>ความมุ่งหมาย <select name="type" class='form-control' >
            <option value="เพื่อทราบ">เพื่อทราบ</option>
            <option value="เพื่อดำเนินการ">เพื่อดำเนินการ</option>
            <option value="เพื่ออนุมัติ">เพื่ออนุมัติ</option>
            </select></td></tr>
    <tr>
      <td>หัวเรื่อง </td><td><input type='text' name='title' class='form-control' /></td></tr>
      <td colspan="2">ระบุรายละเอียดข้อมูลที่ต้องการแจ้งกรรมการรุ่น<br>
          <textarea name="detail" rows="10" cols="30"class='form-control' /></textarea></td></tr>
      <tr><td>ผู้แจ้ง :</td><td>
        <?php $user_info = isset($_SESSION["user_info"]) ? $_SESSION['user_info'] : "";
        echo $user_info;?><input type="hidden"name="name" value="<?php echo $user_info;?>"></td></tr>
      <tr><td colspan="2">แนบรูปภาพ<input type='file' name='record_image' class='form-control' /></td></tr>
      <tr><td colspan="2" align="center"><input type="hidden"name="action" value="newrequest">
              <input type="hidden"name="status" value="แจ้งใหม่">
              <input type='submit' value='Save' class='btn btn-primary' /></td></tr>
      </table>
    </form>
<?php } // end request_form ?>

<?php
function review_request($_id){
  $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/request/'.$_id.'?apiKey='.MLAB_API_KEY);
  $data = json_decode($json);
  $isData=sizeof($data);
  if($isData >0){
    $i=0;
    $name=$data->name;
    $title=$data->title;
    $detail=$data->detail;
    $type=$data->type;
    $urgent=$data->urgent;
    $status=$data->status;
    $img_url=$data->img_url;
?>
    <div class="panel panel-success">
    <div class="panel-heading"><h3 class="panel-title">รายการแจ้งคณะกรรมการรุ่น</h3></div>
    <div class="table-responsive">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
          <table class="table table-sm table-hover table-striped" width="300">
                 <tr><td class="text-nowrap" colspan="2">เรื่อง<input type="text"name="title" value="<?php echo $title;?>"class='form-control' ></td></tr>
                 <tr><td>ผู้แจ้ง</td><td class="text-nowrap"><input type="hidden" name="name" value="<?php echo $name;?>"><?php echo $name;?></td></tr>
                 <tr><td>ความมุ่งหมาย</td><td> <select name="type" class='form-control' >
                <option value="<?php echo $type;?>" selected><?php echo $type;?></option>
                <option value="เพื่อทราบ">เพื่อทราบ</option>
                <option value="เพื่อดำเนินการ">เพื่อดำเนินการ</option>
                <option value="เพื่ออนุมัติ">เพื่ออนุมัติ</option>
              </select></td></tr>
                 <tr><td>ความเร่งด่วน</td><td> <select name="urgent" class='form-control' >
                  <option value="<?php echo $urgent;?>" selected><?php echo $urgent;?></option>
                  <option value="เร่งด่วน">เร่งด่วน</option>
                  <option value="ไม่ด่วน">ไม่ด่วน</option>
                  </select>
                    </td></tr>
               <tr><td colspan="2">รายละเอียด <br><textarea name="detail" rows="10" cols="30"class='form-control'><?php echo $detail;?></textarea></td></tr>
                 <tr><td>สถานะ </td><td><select name="status" class='form-control' >
                   <option value="<?php echo $status;?>" selected><?php echo $status;?></option>
                   <option value="แจ้งใหม่">แจ้งใหม่</option>
                   <option value="รับทราบแล้ว">รับทราบแล้ว รอมติฯ</option>
                   <option value="อนุมัติแล้ว">อนุมัติแล้ว</option>
                   <option value="อยู่ระหว่างดำเนินการ">อยู่ระหว่างดำเนินการ</option>
                   <option value="ดำเนินการแล้ว">ดำเนินการแล้ว</option>
                 </select></td></tr>
                 <tr><td align='center' colspan="2"><img src="<?php echo $img_url;?>" width="300"></td></tr>
                 <tr><td colspan="2">แนบรูปภาพ<input type='file' name='record_image' class='form-control' /></td></tr>
               <tr><td colspan="2" align="center"> <input type="hidden"name="_id" value="<?php echo $_id;?>">
                    <input type="hidden"name="action" value="edited">
                    <input type='submit' value='Submit' class='btn btn-primary' /></td></tr>
               </table>
           </form>
               </div>
               </div>
                      <?php
                     }// end if >0
                }// end function review request
                ?>
<?php function insert_request($newData){
$opts = array('http' => array( 'method' => "POST",
                             'header' => "Content-type: application/json",
                             'content' => $newData
                                         )
                                      );
$url = 'https://api.mlab.com/api/1/databases/crma51/collections/request?apiKey='.MLAB_API_KEY.'';
      $context = stream_context_create($opts);
      $returnValue = file_get_contents($url,false,$context);

      if($returnValue){
     $message= "<div align='center' class='alert alert-success'>รับแจ้งข้อมูล ".$title." เรียบร้อย</div>";
        }else{
     $message= "<div align='center' class='alert alert-danger'>ไม่สามารถบันทึกรับแจ้งการข้อมูลได้</div>";
               }
    $_SESSION["message"]=$message;
      return;
}//end function insert_request
 ?>
 <?php
 function update_field($_id,$field_name,$new_info){

         $newData = '{ "$set" : { "'.$field_name.'" : "'.$new_info.'"} }';
         $opts = array('http' => array( 'method' => "PUT",
                                        'header' => "Content-type: application/json",
                                        'content' => $newData
                                                    )
                                                 );
         $url = 'https://api.mlab.com/api/1/databases/crma51/collections/request/'.$_id.'?apiKey='.MLAB_API_KEY;
                 $context = stream_context_create($opts);
                 $returnValue = file_get_contents($url,false,$context);
                 return;
 }
  ?>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
