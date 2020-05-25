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

    <div class="container theme-showcase" role="main">
    <div class="jumbotron">
      <?php
        $action= isset($_GET['action']) ? $_GET['action'] : "";
        $_id = isset($_GET['_id']) ? $_GET['_id'] : "";
        if(($action == 'review') && (!empty($_id))){
          echo "action is review and not empty _id is $_id, call function review_request";
          review_request($_id);
        }else{
          new_request_form();
        }
        if(isset($_POST['formSubmit'])){
          if(isset($_POST['_id'])){$_id=$_POST['_id'];}else{$_id=''; }
          if(isset($_POST['name'])){$name=$_POST['name'];}else{$name=''; }
          if(isset($_POST['title'])){$title=$_POST['title'];}else{$title=''; }
          if(isset($_POST['detail'])){$detail=$_POST['detail'];}else{$detail=''; }
          if(isset($_POST['type'])){$type=$_POST['type'];}else{$type=''; }
          if(isset($_POST['urgent'])){$urgent=$_POST['urgent'];}else{$urgent=''; }
          if(isset($_POST['status'])){$status=$_POST['status'];}else{$status=''; }
          $newData = json_encode(array(
            'name'=>$name,
            'title' => $title,
            'detail' => $detail,
            'type' => $type ,
            'urgent' => $urgent,
            'status'=>'แจ้งใหม่') );
            $type_form_Submit=$_POST['formSubmit'];
            if($type_form_Submit=='newrequest'){
              insert_request($newData);
            }elseif ($type_form_Submit=='edited') {

              $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/request/'.$_id.'?apiKey='.MLAB_API_KEY);
            $data = json_decode($json);
            $isData=sizeof($data);
            if($isData >0){

                 $name_db=$data->name;echo $name_db.$name;
                 //if($name!=$name_db){update_field($_id,'name',$name);}

                 $title_db=$data->title;echo $title_db.$title;
                 //if($title!=$title_db){update_field($_id,'title',$title);}

                 $detail_db=$data->detail;echo $detail_db.$detail;
                 //if($detail!=$detail_db){update_field($_id,'detail',$detail);}

                 $type_db=$data->type;echo $type_db.$type;
                 //if($type!=$type_db){update_field($_id,'type',$type);}

                 $urgent_db=$data->urgent;echo $urgent_db.$urgent;
                 //if($urgent!=$urgent_db){update_field($_id,'urgent',$urgent);}

                 $status_db=$data->status;echo $status_db.$status;
                 //if($status!=$status_db){update_field($_id,'status',$status);}

}// end if data>0
}else{

}// Submit formbut not newrequest nor edited
        } // end if isset _POST['formSubmit']
          ?>
<?php show_all_request();?>
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
      <td class="text-nowrap"><?php echo $title;?></td>
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
               	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                   <table class='table table-hover table-responsive table-bordered'>
                       <tr><td>แจ้งเรื่องต่าง ๆ ให้คณะกรรมการรุ่นทราบ</td></tr>
                       <tr><td>
                       <select name="urgent">
                       <option value="เร่งด่วน">เร่งด่วน</option>
                       <option value="ไม่ด่วน">ไม่ด่วน</option>
                       </select>
                         <select name="type">
                           <option value="เพื่อทราบ">เพื่อทราบ</option>
                           <option value="เพื่อพิจารณาดำเนินการ">เพื่อพิจารณาดำเนินการ</option>
                           <option value="เพื่ออนุมัติ">เพื่ออนุมัติ</option>
                         </select></td></tr>
                         <tr>
                             <td>หัวเรื่อง<input type='text' name='title' class='form-control' /></td>
                             </tr>
                           <td>ระบุรายละเอียดข้อมูลที่ต้องการแจ้งกรรมการรุ่น
                           <textarea name="comment" rows="10" cols="30"class='form-control' /></textarea></td>
                       </tr>
                   <tr><td>
                       ผู้แจ้ง : <input type='hidden' name='name' value="<?php $user_info = isset($_SESSION["user_info"]) ? $_SESSION['user_info'] : ""; echo $user_info;?>" /><?php echo $user_info;?>
                     </td>
                 </tr>
                       <tr><td><input type="hidden"name="formSubmit" value="newrequest">
                               <input type='submit' value='Save' class='btn btn-primary' />

                           </td>
                       </tr>
                   </table>
               </form>
 <?php
  } // end request_form ?>

<?php
function review_request($_id){
                 $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/request/'.$_id.'?apiKey='.MLAB_API_KEY);
                 $data = json_decode($json);
                 $isData=sizeof($data);print_r($data);
                 if($isData >0){
                   $i=0;
                      $name=$data->name;
                      $title=$data->title;
                      $detail=$data->detail;
                      $type=$data->type;
                      $urgent=$data->urgent;
                      $status=$data->status;
                   ?>
                     <div class="panel panel-success">
                       <div class="panel-heading">
                         <h3 class="panel-title">รายการแจ้งคณะกรรมการรุ่น</h3>
                       </div>
                       <div class="table-responsive">
                         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                           <table class="table table-sm table-hover table-striped">
                 <tr><td class="text-nowrap"><input type="text"name="title" value="<?php echo $title;?>"></td></tr>
                 <tr><td class="text-nowrap"><input type="text"name="name" value="<?php echo $name;?>"></td></tr>
              <tr><td><select name="type">
                <option value="<?php echo $type;?>" selected><?php echo $type;?></option>
                <option value="เพื่อทราบ">เพื่อทราบ</option>
                <option value="เพื่อพิจารณาดำเนินการ">เพื่อพิจารณาดำเนินการ</option>
                <option value="เพื่ออนุมัติ">เพื่ออนุมัติ</option>
              </select></td></tr>
                 <tr><td> <select name="urgent">
                  <option value="<?php echo $urgent;?>" selected><?php echo $urgent;?></option>
                  <option value="เร่งด่วน">เร่งด่วน</option>
                  <option value="ไม่ด่วน">ไม่ด่วน</option>
                  </select>
                    </td></tr>
               <tr><td><input type="textarea" name="detail"><?php echo $detail;?></textarea></td></tr>
                 <tr><td><select name="type">
                   <option value="<?php echo $status;?>" selected><?php echo $status;?></option>
                   <option value="แจ้งใหม่">แจ้งใหม่</option>
                   <option value="ดำเนินการแล้ว">ดำเนินการแล้ว</option>
                   <option value="เพื่ออนุมัติแล้ว">เพื่ออนุมัติแล้ว</option>
                 </select></td></tr>
                <tr><td> <input type="hidden"name="_id" value="<?php echo $_id;?>">
                    <input type="hidden"name="formSubmit" value="edited">
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
