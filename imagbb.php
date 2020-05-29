<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
if(!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin"){
    header("location: index.php?message=You are not admin.");
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
<?php $imgbb_key="6c23a11220bb2c1f7b9406175f3b8cbc";?>

    <div class="container theme-showcase" role="main">
    <div class="jumbotron">
       <?php if(isset($_SESSION["message"])){$message=$_SESSION['message'];echo $message;}?>
      <div class="page-header">
          <table  class='table table-hover table-responsive table-bordered'>
            <tr><td><h3>ข้อมูลเพื่อน ๆ ล่าสุด</h3></td></tr>
          </table>

      </div>
      <?php
      /*
      https://api.imgbb.com/1/upload
      curl --location --request POST "https://api.imgbb.com/1/upload?key=$imgbb_key" --form "image=R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
*/
      if(isset($_POST['formSubmit'])){

        echo "\n if isset formSubmit ";
        $name = isset($_POST['name']) ? $_POST['name'] : "";
        $imgbb = isset($_POST['imgbb']) ? $_POST['imgbb'] : "";
        echo "name is ".$name;
        echo "\n the imgbb is "; print_r($imgbb);
        insert_imgbb($name,$imgbb);
        show_imgbb();
      }else{
        show_imgbb();
      }

function show_imgbb(){

  echo "\n inside function show_imgbb. ";
      $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/imgbb?apiKey='.MLAB_API_KEY);
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
                      <th>ลำดับ</th><th>ID</th>
            <th>name</th>
            <th>imgbb</th></tr>
          </thead><tbody>
        <?php
        foreach($data as $rec){
          $i++;
          $_id=$rec->_id;
          foreach($_id as $rec_id){
          $_id=$rec_id;
          }
           $name=$rec->name;
           $imgbb=$rec->imgbb;
           ?>
      <tr><td><?php echo $i;?></td>
                       <td class="text-nowrap">
                         <?php echo $name;?></td>
                       <td><?php echo $imgbb;?></td></tr>
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
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table  class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>image</td>
            <td><input type="image" name="imgbb"></td>
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

function insert_imgbb($name,$imgbb){
echo "\n inside function insert_imgbb. ";echo "name is ".$name;
echo "\n the imgbb is "; print_r($imgbb);
  $newData = json_encode(array(

    'name'=>$name,
    'imgbb'=>$imgbb );
  $opts = array('http' => array( 'method' => "POST",
                                 'header' => "Content-type: application/json",
                                 'content' => $newData
                                             )
                                          );
  $url = 'https://api.mlab.com/api/1/databases/crma51/collections/imgbb?apiKey='.MLAB_API_KEY;
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
