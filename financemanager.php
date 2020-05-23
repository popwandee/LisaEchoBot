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
  <?php include 'navigation.html';?>
    <div class="container theme-showcase" role="main">
    <div class="jumbotron">
      <h1>บัญชีเงินรุ่น</h1>
      <p>เตรียมทหาร รุ่นที่ 40 จปร.รุ่นที่ 51</p>

	  <?php
    if(isset($_GET['action']) && ($_GET['action']=='delete')){
      $record_id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
      if(isset($record_id)){
        delete_record($record_id);
      }
  }
    $data = isset($_POST['data']) ? $_POST['data'] : "";
    if(isset($_POST['form_no'])){
    $form_no=$_POST['form_no'];
     switch ($form_no){
       case "sum_record":
            $sum = isset($_POST['sum']) ? $_POST['sum'] : die('ERROR: Summary not found.');
            sum_record($sum);
            break;

       case "add_record" :
       $username = isset($_POST['username']) ? $_POST['username'] : "";
       $record = isset($_POST['record']) ? $_POST['record'] : "";
       $add = isset($_POST['add']) ? $_POST['add'] : "";
       $sub = isset($_POST['sub']) ? $_POST['sub'] : "";
       insert_finance_record($username,$record,$add,$sub);
       $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/finance?apiKey='.MLAB_API_KEY);

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
            $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : "";
            $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : 0;
            $type = isset($_POST['type']) ? $_POST['type'] : 0;
            update_user($user_id,$fullname,$type);
          }else{
            show_form($user_id);
            }
            $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/manager?apiKey='.MLAB_API_KEY);

          break;
       default :
          $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/finance?apiKey='.MLAB_API_KEY);
      }//end switch
      $data = json_decode($json);
      $isData=sizeof($data);
      if($isData >0){
          showdata($data);
      }else{
        echo "No result from data";
      }
    }//end if isset form_no
  else{
    $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/finance?apiKey='.MLAB_API_KEY);
    $data = json_decode($json);
    $isData=sizeof($data);
    if($isData >0){
        showdata($data);
    }else{
      echo "No result from data";
    }

  }
     ?>

     <?php
     function showdata($data)
     {
       echo "<table class='table table-hover table-responsive table-bordered'>";//start table
       //creating our table heading
       echo "<tr>";
         echo "<th>ลำดับ</th>";
         echo "<th>รายการเคลื่อนไหว</th>";
         echo "<th>รับ</th>";
         echo "<th>จ่าย</th>";
         echo "<th>คงเหลือ</th>";
         echo "<th>Action</th>";
       echo "</tr>";
       $id=0;$sum=0;
       foreach($data as $rec){
       $id++;
       $_id=$rec->_id;
       foreach($_id as $rec_id){
       $_id=$rec_id;
       }
       $record=$rec->record;
       $add=$rec->add;
       $sub=$rec->sub;
       $sum=$sum+$add-$sub;
       // creating new table row per record
       echo "<tr>";
         echo "<td width='10%'>{$id}</td>";
         echo "<td width='30%'>{$record}</td>";
         echo "<td width='20%'>{$add}</td>";
         echo "<td width='10%'>{$sub}</td>";
         echo "<td width='20%'>{$sum}</td>";
         echo "<td width='10%'>";
         if(isset($_SESSION['type']) && (($_SESSION['type'])=='เหรัญญิก')){?>
         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
           <input type="hidden" name="user_id" value="<?php echo $_id; ?>">
           <input type='hidden' name='form_no' value='user_edit'>
           <input type='hidden' name='edited' value='0'>
           <button type="submit" class="btn btn-xs btn-warning">แก้ไข</button>
           </form>
           <a href="financemanager.php?action=delete&id=<?php echo $_id; ?>">ลบรายการ</a>

           <?php
         }//end ifเหรัญญิก
         echo "</td>";
       echo "</tr>";
     }//end foreach

      if(isset($_SESSION['type']) && (($_SESSION['type'])=='เหรัญญิก')){?>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <tr><td><input type="hidden" name="username" value="<?php echo $username; ?>">
         <input type='hidden' name='form_no' value='add_record'></td>
         <td><input type='text' name='record'></td>
         <td><input type='text' name='add'></td>
         <td><input type='text' name='sub'></td>
         <td colspan="2"><button type="submit" class="btn btn-xs btn-info">เพิ่มรายการ</button></td>
         </tr></form>

         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
           <tr><td colspan="4"><input type="input" name="sum" value="<?php echo $sum; ?>">
            <input type='hidden' name='form_no' value='sum_record'></td>
            <td colspan="2"><button type="submit" class="btn btn-xs btn-info">เพิ่มรายการ</button></td>
            </tr></form>
         <?php
        }//end ifเหรัญญิก
       echo "</table>";// end table

     }// end function showdata
     ?>
     <span class="label label-info">
<?php if(isset($_SESSION["message"])){
  $message=$_SESSION['message'];
  echo $message;$_SESSION['message']='';
}?>
<?php $message = isset($_GET['message']) ? $_GET['message'] : "";   echo $message; ?>
</span>

<?php
function insert_finance_record($username,$record,$add,$sub){
$newData = json_encode(array(
  'username' => $username,
  'record' => $record,
  'add' => $add,
  'sub' => $sub) );
$opts = array('http' => array( 'method' => "POST",
                               'header' => "Content-type: application/json",
                               'content' => $newData
                                           )
                                        );
$url = 'https://api.mlab.com/api/1/databases/crma51/collections/finance?apiKey='.MLAB_API_KEY;
        $context = stream_context_create($opts);
        $returnValue = file_get_contents($url,false,$context);
        if($returnValue){
          $_SESSION['message']='=> สำเร็จ.';
           header('Location: financemanager.php?message=Completed');
          }else{
          $_SESSION['message']='=> ไม่สำเร็จ.';
           header('Location: financemanager.php?message=InCompleted');
                 }
} // end function insert_finance_record
 ?>

         <div><!-- class="jumbotron"-->
      </div> <!-- container theme-showcase -->

      <?php
      function sum_record($sum){
        $newData = '{ "$set" : { "sum" : "'.$sum.'"} }';
        $opts = array('http' => array( 'method' => "PUT",
                                       'header' => "Content-type: application/json",
                                       'content' => $newData
                                                   )
                                                );
        $url = 'https://api.mlab.com/api/1/databases/crma51/collections/friend/5ec50995e7179a6b6362e1f4?apiKey='.MLAB_API_KEY;
                $context = stream_context_create($opts);
                $returnValue = file_get_contents($url,false,$context);
      } // end function sum_record
       ?>

       <?php
function delete_record($id){
  $opts = array('http' => array( 'method' => "DELETE",
                                 'header' => "Content-type: application/json",
                                             )
                                          );
  $url = 'https://api.mlab.com/api/1/databases/crma51/collections/finance/'.$id.'?apiKey='.MLAB_API_KEY.'';
          $context = stream_context_create($opts);
          $returnValue = file_get_contents($url,false,$context);
  }
        ?>
        <?php
        // show error
        catch(PDOException $exception){
            die('ERROR: ' . $exception->getMessage());
         ?>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
