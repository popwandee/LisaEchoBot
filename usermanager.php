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

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
if(!isset($_SESSION["type"]) || $_SESSION["type"] == "สมาชิก"){
    header("location: index.php?message=You are not admin.");
    exit;
}


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
      <h1>AFAPS40 - CRMA51</h1>
      <p>เตรียมทหาร รุ่นที่ 40 จปร.รุ่นที่ 51</p>
    <div class='table-responsive'>
      <table align='center' class='table table-hover table-responsive table-bordered'>
  <tr>  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
   <td align='right'>ค้นหาด้วยชื่อ<br><input type='text' name='name' class='form-control' />
      <br><input type='hidden' name='form_no' value='search_name'>
      <input type='submit' value='ค้นหา' class='btn btn-primary' />
</td></form>
 <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <td align='right'>ค้นหาด้วยจังหวัด<Br><?php select_province();?></td>
</form>
 <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <td align='right'>ค้นหาด้วยหมายเลขโทรศัพท์
      <br><input type='text' name='Tel1' class='form-control' />
    <br><input type='hidden' name='form_no' value='search_phone' />
      <input type='submit' value='ค้นหา' class='btn btn-primary'/>
</td></form>
 <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
  <td><input type='hidden' name='form_no' value='show_all_user'>
    <input type='submit' value='แสดง Username ทั้งหมด' class='btn btn-primary' />
</td></form>
</tr></table>
</div> <!-- class='table-responsive'-->
	  <?php
    if(isset($_POST['form_no'])){
    $form_no=$_POST['form_no']; //echo "form ".$form_no;
     switch ($form_no){
       case "search_name" ://echo "\nCase Search by name";
           if(isset($_POST['name'])){$name=$_POST['name'];}
           $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY.'&q={"name":{"$regex":"'.$name.'"}}');
            break;
       case "search_province" : //echo "\nCase search by province";
           if(isset($_POST['province'])){$province=$_POST['province'];}
           $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY.'&q={"province":"'.$province.'"}');
            break;
       case "search_phone" : //echo "\nCase search by province";
           if(isset($_POST['Tel1'])){$Tel1=$_POST['Tel1'];}
           $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY.'&q={"Tel1":"'.$Tel1.'"}');
            break;
       case "show_all_user" : //echo "\nCase show all user.";
           $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY);
            break;
      case "user_approved" : //echo "\nCase approve user";
           $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : "";
           $approved = isset($_POST['approved']) ? $_POST['approved'] : 0;
           user_approved($user_id,$approved);
           $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY);

           break;
      case "user_edit" : //echo "\nCase edit user";
      $_id = isset($_POST['_id']) ? $_POST['_id'] : "";
      $edited = isset($_POST['edited']) ? $_POST['edited'] : 0;
          if($edited){
            //echo "\nGet data from edit user form.";
            // Get data from database to Compare
          $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend/'.$_id.'?apiKey='.MLAB_API_KEY);
          $data = json_decode($json);
          $isData=sizeof($data);
          if($isData >0){
            //echo "\nGet data from DB are "; //print_r($data);
               $rank=$data->rank;$update_rank = isset($_POST['rank']) ? $_POST['rank'] : "";
               if($rank!=$update_rank){update_field($_id,'rank',$update_rank);}

               $name=$data->name;$update_name = isset($_POST['name']) ? $_POST['name'] : "";
               if($name!=$update_name){update_field($_id,'name',$update_name);}

               $lastname=$data->lastname;$update_lastname = isset($_POST['lastname']) ? $_POST['lastname'] : "";
               if($lastname!=$update_lastname){update_field($_id,'lastname',$update_lastname);}

               $position=$data->position;$update_position = isset($_POST['position']) ? $_POST['position'] : "";
               if($position!=$update_position){update_field($_id,'position',$update_position);}

               $province=$data->province;$update_province = isset($_POST['province']) ? $_POST['province'] : "";
               if($province!=$update_province){update_field($_id,'province',$update_province);}

               $Email=$data->Email;$update_Email = isset($_POST['Email']) ? $_POST['Email'] : "";
               if($Email!=$update_Email){update_field($_id,'Email',$update_Email);}

               $Tel1=$data->Tel1;$update_Tel1 = isset($_POST['Tel1']) ? $_POST['Tel1'] : "";
               if($Tel1!=$update_Tel1){update_field($_id,'Tel1',$update_Tel1);}

               $LineID=$data->LineID;$update_LineID = isset($_POST['LineID']) ? $_POST['LineID'] : "";
               if($LineID!=$update_LineID){update_field($_id,'LineID',$update_LineID);}

               $comment=$data->comment;$update_comment = isset($_POST['comment']) ? $_POST['comment'] : "";
               if($comment!=$update_comment){update_field($_id,'comment',$update_comment);}

               $type = $data->type;$update_type = isset($_POST['type']) ? $_POST['type'] : "";
               if($type!=$update_type){update_field($_id,'type',$update_type);}

               if (!empty($_FILES['record_image'])) { //record_image
                 $files = $_FILES["record_image"]['tmp_name'];
                 $option= array("public_id" => "$Tel1");
                 $cloudUpload = \Cloudinary\Uploader::upload($files,$option);
                 $img_url = $cloudUpload['secure_url'];
                  if(!empty($img_url)){
                   $_SESSION['message']=$_SESSION['message']." got img_url is ".$img_url;
                   update_field($_id,'img_url',$img_url);
                 }
               }
               // retrieve database
               $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY);
               $data = json_decode($json);
               $isData=sizeof($data);
               if($isData >0){
                   showdata($data);
                 }
             }

          }else{//echo "\nShow form for edit user";
            show_form($_id);
            }
          //  $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY);

          break;
       default :

      }//end switch
      $data = json_decode($json);
      $isData=sizeof($data);
      if($isData >0){
          showdata($data);
      }
    }//end if isset form_no
    else{
      $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY);
      $data = json_decode($json);
      $isData=sizeof($data);
      if($isData >0){
          showdata($data);
        }
    }
    if(isset($_SESSION['message'])){
      $message=$_SESSION['message'];echo $message; $_SESSION['message']='';
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
     <?php
     function showdata($data)
     { ?>
       <div class='table-responsive'>
       <table class='table table-hover table-responsive table-bordered'>
       <tr><th>ลำดับ</th><th>ชื่อ สกุล</th><th>ตำแหน่ง</th>
         <th>โทรศัพท์</th><th>ประเภทสมาชิก</th><th>Approved</th><th>Action</th>
       </tr>
       <?php
       $id=0;
       foreach($data as $rec){
       $id++;
       $_id=$rec->_id;
       foreach($_id as $rec_id){
       $_id=$rec_id;
       }
       $rank=$rec->rank;
       $name=$rec->name;
       $lastname=$rec->lastname;
       $position=$rec->position;
       $Tel1=$rec->Tel1;
       $type=$rec->type;
       $approved=$rec->approved;
?>
       <tr><td width='10%'><?php echo "{$id}";?></td>
         <td width='30%' class='text-nowrap'><a href="friend_preview.php?_id=<?php echo $_id; ?>"><?php echo "{$rank} {$name} {$lastname}";?></a></td>
         <td width='20%'><?php echo "{$position}";?></td>
         <td width='20%'><?php echo "{$Tel1}";?></td>
         <td width='10%'><?php echo "{$type}";?></td>
         <td width='20%'>
          <?php if($approved){ ?>
                  <button type="button" class="btn btn-xs btn-success">อนุมัติแล้ว</button>
                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
                    <input type="hidden" name="user_id" value="<?php echo $_id;?>">
                    <input type='hidden' name='form_no' value='user_approved'>
                    <input type='hidden' name='approved' value='1'>
                    <button type="submit" class="btn btn-xs btn-warning">ยกเลิกการอนุมัติ</button>
                    </form>
                <?php }else{ // not approved ?>
                  <button type="button" class="btn btn-xs btn-danger">ยังไม่อนุมัติ</button>
                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $_id;?>">
                    <input type='hidden' name='form_no' value='user_approved'>
                    <input type='hidden' name='approved' value='0'>
                    <button type="submit" class="btn btn-xs btn-warning">อนุมัติ</button>
                    </form>
                <?php } //end id approved ?>
         </td><td width='10%'>
         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
           <input type="hidden" name="user_id" value="<?php echo $_id; ?>">
           <input type='hidden' name='form_no' value='user_edit'>
           <input type='hidden' name='edited' value='0'>
           <button type="submit" class="btn btn-xs btn-warning">แก้ไข</button>
           </form>
         </td>
       </tr>
     <?php }// end table ?>
     </tbody>
      </table>
</div> <!-- class='table-responsive'-->
  <?php  }// end function   ?>
     <span class="label label-info">
<?php if(isset($_SESSION["message"])){$message=$_SESSION['message'];echo $message;$_SESSION['message']='';}?>
<?php $message = isset($_GET['message']) ? $_GET['message'] : "";   echo $message; ?>
</span>
<?php
function user_approved($_id,$approved){
  if($approved){
  $newData = '{ "$set" : { "approved" : 0 } }';
  $_SESSION['message']='ยกเลิกการอนุมัติสิทธิ์เข้าใช้ระบบ';
}else{
  $newData = '{ "$set" : { "approved" : 1 } }';
  $_SESSION['message']='การอนุมัติสิทธิ์เข้าใช้ระบบ ';
}
  $opts = array('http' => array( 'method' => "PUT",
                                 'header' => "Content-type: application/json",
                                 'content' => $newData
                                             )
                                          );
  $url = 'https://api.mlab.com/api/1/databases/crma51/collections/friend/'.$_id.'?apiKey='.MLAB_API_KEY.'';
          $context = stream_context_create($opts);
          $returnValue = file_get_contents($url,false,$context);
          if($returnValue){
            $_SESSION['message']=$_SESSION['message'].'=> สำเร็จ.';
         		 header('Location: usermanager.php?message=Approved');
  	        }else{
            $_SESSION['message']=$_SESSION['message'].'=> ไม่สำเร็จ.';
  		       header('Location: usermanager.php?message=CannotApproved');
                   }
}
 ?>

 <?php
function show_form($_id){
  //echo "\nin Fuction Show form, get user_id is ";print_r($user_id);
  $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend/'.$_id.'?apiKey='.MLAB_API_KEY);

$data = json_decode($json);
$isData=sizeof($data);
if($isData >0){
  //echo "\nGet data from DB are "; //print_r($data);
     $rank=$data->rank;
     $name=$data->name;
     $lastname=$data->lastname;
     $position=$data->position;
     $province=$data->province;
     $Email=$data->Email;
     $Tel1=$data->Tel1;
     $LineID=$data->LineID;
     $comment=$data->comment;
     $type = $data->type;
     $img_url = $data->img_url;
        ?>
        <table><tr><td>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">

        <table  class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>ยศ ชื่อ สกุล</td>
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
                <td><?php select_province($province)?>
    </td>
            </tr>
            <tr>
                <td colspan="2"><img src="<?php echo $img_url;?>"></td>
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
                <td>รายละเอียดเพิ่มเติม</td>
                <td><textarea name="comment" rows="10" cols="30"class='form-control' /><?php echo $comment;?></textarea></td>
            </tr>
        <tr>
            <td>เปลี่ยนรูปภาพ</td>
            <td><input type="file" name="record_image" class="form-control" accept="image/*"></td>
        </tr>
            <tr>
                <td></td>
      <br>ประเภทสมาชิก :<select name="type">
<option value="<?php echo $type;?>" selected><?php echo $type;?></option>
<option value="สมาชิก">สมาชิก</option>
<option value="กรรมการ">กรรมการ</option>
<option value="เหรัญญิก">เหรัญญิก</option>
<option value="admin">Admin</option>
</select></td><td>
      <input type="hidden" name="user_id" value="<?php echo $_id;?>">
      <input type='hidden' name='form_no' value='user_edit'>
      <input type='hidden' name='edited' value='1'>
      <button type="submit" class="btn btn-xs btn-warning">ยืนยัน</button>
      </form>
    </td></tr></table>
      <?php
    }// if isData > 0;
      exit;
} // end function show_form

  ?>

<?php
function update_field($_id,$field_name,$new_info){

        $newData = '{ "$set" : { "'.$field_name.'" : "'.$new_info.'"} }';
        $opts = array('http' => array( 'method' => "PUT",
                                       'header' => "Content-type: application/json",
                                       'content' => $newData
                                                   )
                                                );
        $url = 'https://api.mlab.com/api/1/databases/crma51/collections/friend/'.$_id.'?apiKey='.MLAB_API_KEY;
                $context = stream_context_create($opts);
                $returnValue = file_get_contents($url,false,$context);

}
 ?>
         <div><!-- class="jumbotron"-->
      </div> <!-- container theme-showcase -->


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
