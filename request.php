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
    <span class="label label-primary">แจ้งกรรมการรุ่นเพื่อทราบและพิจารณา</span>
    <?php show_all_request();?>
    <?php //request_form();?>
    </div>
    <div class="jumbotron">
      <?php
        $action= isset($_GET['action']) ? $_GET['action'] : "";
        $_id = isset($_GET['_id']) ? $_GET['_id'] : "";
        if(($action == 'review') && (!empty($_id))){
          //review_request($_id);
          }
        if(isset($_POST['formSubmit'])){
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
                  header("location: request.php");
                    exit;

        } // end if isset _POST['formSubmit']
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
        <th>ประเภท</th>
      <th>สถานะ</th></tr>
          </thead><tbody>
        <?php
        foreach($data as $rec){
          $i++;
          $_id=$rec->_id;
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
      <td><?php echo $type;?></td>
      <td><?php echo $status;?> <a href="request.php?action=review&id=<?php echo $_id;?>">รายละเอียด</a></td>
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



<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
