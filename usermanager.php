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
      <h1>AFAPS40 - CRMA51</h1>
      <p>เตรียมทหาร รุ่นที่ 40 จปร.รุ่นที่ 51</p>
      <?php if(isset($_SESSION["message"])){$message=$_SESSION['message'];echo $message;}else{$_SESSION['message']='';}?>
    <?php $message = isset($_GET['message']) ? $_GET['message'] : "";   echo $message; ?>
<table align='center'>
  <tr><td>
  	 <h3>ค้นหาตาม Username </h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Username<input type='text' name='username' class='form-control' /></td>
            <td><input type='hidden' name='form_no' value='search_username'><input type='submit' value='ค้นหา' class='btn btn-primary' /></td>
        </tr>
    </table>
</form>
</td><td>
<h3>ค้นหาตามชื่อ </h3>
 <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<table class='table table-hover table-responsive table-bordered'>
<tr>
    <td>ชื่อ<input type='text' name='fullname' class='form-control' /></td>
    <td><input type='hidden' name='form_no' value='search_name'><input type='submit' value='ค้นหา' class='btn btn-primary' /></td>
</tr>
</table>
</form>
</td><td>
<h3>แสดงรายชื่อทั้งหมด </h3>
 <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<table class='table table-hover table-responsive table-bordered'>
<tr>
    <td><input type='hidden' name='form_no' value='show_all_user'><input type='submit' value='แสดง User ทั้งหมด' class='btn btn-primary' /></td>
</tr>
</table>
</form>
</td></tr></table>
	  <?php
    if(isset($_POST['form_no'])){
    $form_no=$_POST['form_no'];
     switch ($form_no){
       case "search_name" :
           if(isset($_POST['fullname'])){$name=$_POST['fullname'];}
           $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/manager?apiKey='.MLAB_API_KEY.'&q={"name":{"$regex":"'.$name.'"}}');
            break;
       case "search_username" :
           if(isset($_POST['username'])){$username=$_POST['username'];}
           $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/manager?apiKey='.MLAB_API_KEY.'&q={"username":"'.$username.'"}');
            break;
       case "show_all_user" :
           $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/manager?apiKey='.MLAB_API_KEY);
            break;
      case "user_approved" :
           $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : "";
           $approved = isset($_POST['approved']) ? $_POST['approved'] : 0;
           user_approved($user_id,$approved);
           $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/manager?apiKey='.MLAB_API_KEY);

           break;
      case "user_edit" :
      $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : "";
      $edited = isset($_POST['edited']) ? $_POST['edited'] : 0;
          if($edited){
            echo "get data from form, go to update database.";
          }else{
            show_form($user_id);
            }
          break;
       default :
            foo("no");
      }//end switch
      $data = json_decode($json);
      $isData=sizeof($data);
      if($isData >0){
          showdata($data);
      }
    }//end if isset form_no
     ?>

     <?php
     function showdata($data)
     {
       echo "<table class='table table-hover table-responsive table-bordered'>";//start table
       //creating our table heading
       echo "<tr>";
         echo "<th>ลำดับ</th>";
         echo "<th>ชื่อ สกุล</th>";
         echo "<th>Username</th>";
         echo "<th>Type</th>";
         echo "<th>Approved</th>";
         echo "<th>Action</th>";
       echo "</tr>";
       $id=0;
       foreach($data as $rec){
       $id++;
       $_id=$rec->_id;
       foreach($_id as $rec_id){
       $_id=$rec_id;
       }
       $fullname=$rec->fullname;
       $username=$rec->username;
       $type=$rec->type;
       $approved=$rec->approved;

       // creating new table row per record
       echo "<tr>";
         echo "<td>{$id}</td>";
         echo "<td>{$fullname}</td>";
         echo "<td>{$username}</td>";
         echo "<td>{$type}</td>";
         echo "<td>";
                if($approved){
                  echo "อนุมัติแล้ว";
                  ?>
                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $_id;?>">
                    <input type='hidden' name='form_no' value='user_approved'>
                    <input type='hidden' name='approved' value='1'>
                    <button type="submit" class="btn btn-xs btn-warning">ยกเลิกการอนุมัติ</button><?php echo $_id; ?>
                    </form>
                      <?php
                }else{
                  echo "ยังไม่อนุมัติ";
                  ?>
                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $_id;?>">
                    <input type='hidden' name='form_no' value='user_approved'>
                    <input type='hidden' name='approved' value='0'>
                    <button type="submit" class="btn btn-xs btn-warning">อนุมัติ</button><?php echo $_id; ?>
                    </form>

                      <?php
                }

         echo"</td>";
         echo "<td>";
         ?>
         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
           <input type="hidden" name="user_id" value="<?php echo $_id; ?>">
           <input type='hidden' name='form_no' value='user_edit'>
           <input type='hidden' name='edited' value='0'>
           <button type="submit" class="btn btn-xs btn-warning">แก้ไข</button><?php echo $_id; ?>
           </form>
           <?php
         echo "</td>";
       echo "</tr>";
       }
       // end table
       echo "</table>";
       // end function
     }
     ?>

<?php
function user_approved($user_id,$approved){
  if($approved){
  $newData = '{ "$set" : { "approved" : 0 } }';
}else{
  $newData = '{ "$set" : { "approved" : 1 } }';
}
  $opts = array('http' => array( 'method' => "PUT",
                                 'header' => "Content-type: application/json",
                                 'content' => $newData
                                             )
                                          );
  $url = 'https://api.mlab.com/api/1/databases/crma51/collections/manager/'.$user_id.'?apiKey='.MLAB_API_KEY.'';
          $context = stream_context_create($opts);
          $returnValue = file_get_contents($url,false,$context);
          if($returnValue){
            $_SESSION['message']='User id approved is'.$user_id;
         		 header('Location: usermanager.php?message=Approved');
  	        }else{
              $_SESSION['message']='User id not approved is'.$user_id;
  		       header('Location: usermanager.php?message=CannotApproved');
                   }
}
 ?>

 <?php
function show_form($user_id){
  echo $user_id;
  $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/manager?apiKey='.MLAB_API_KEY.'&q={"_id":{ "$oid":"'.$user_id.'" }}');
  $data = json_decode($json);
  $isData=sizeof($data);
  $i=0;
  if($isData >0){
     // มีข้อมูลผู้ใช้อยู่
  foreach($data as $rec){
    $i++;echo $i;
     $fullname=$rec->fullname;echo $fullname;
     $username=$rec->username;echo $username;
     $type=$rec->type;echo $type:

   } //end foreach
}// end isData>0

        ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
      <?php echo "Username :".$username;?>
      <br>ชื่อ นามสกุล :<input type='text' name='fullname'value="<?php echo $fullname;?>" class='form-control' />
      <br>ประเภทสมาชิก :<input type='text' name='type' value="<?php echo $type;?>" class='form-control' />
      <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
      <input type='hidden' name='form_no' value='user_edit'>
      <input type='hidden' name='edited' value='1'>
      <button type="submit" class="btn btn-xs btn-warning">ยืนยัน</button>
      </form>
      <?php


      exit;
} // end function show_form

  ?>
         <div><!-- class="jumbotron"-->
      </div> <!-- container theme-showcase -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
