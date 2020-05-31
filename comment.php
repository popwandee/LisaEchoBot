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
