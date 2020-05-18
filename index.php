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
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <h1>AFAPS40 - CRMA51</h1>
      <p>เตรียมทหาร รุ่นที่ 40 จปร.รุ่นที่ 51</p>
    </div>

  <!-- Main Content -->
  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-md-10 mx-auto">
        <div class="post-preview">
          <a href="post.html">
            <h2 class="post-title"></h2>
            <h3 class="post-subtitle">
              <?php
             if(isset($_POST['name'])&&(isset($_POST['_id']))&&(isset($_POST['comment']))){
             	// รับค่าข้อมูลจาก POST ให้ตัวแปร
              $name =	htmlspecialchars(strip_tags($_POST['name']));
              $_id=htmlspecialchars(strip_tags($_POST['_id']));
              $comment =	htmlspecialchars(strip_tags($_POST['comment']));

             // นำข้อมูลเข้าเก็บในฐานข้อมูล
             $newData = json_encode(array('_id' => $_id,
             			     'name' => $name,
             			     'comment' => $comment) );
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
             		   	header("location: search.php");
                 			exit;
                     // ยังไม่มีการโพสต์ข้อมูลจากแบบฟอร์ม
                 }else{
                     echo "<div align='center' class='alert alert-success'>แจ้งแก้ไขข้อมูล".$dateTimeToday."</div>";

             }  // end of if(isset($_POST['_id'])&&isset($_POST['name']))

             if(isset($_POST['name'])){
             	$name=$_POST['name'];
             }else{
             $name='';
             }
             ?>
             	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                 <table class='table table-hover table-responsive table-bordered'>

                     <tr>
                         <td>ชื่อ สมาชิกที่ต้องการให้แก้ไขข้อมูล</td>
                         <td><input type='text' name='name' value="<?php echo $name;?>" class='form-control' /></td>
                     </tr>
                     <tr>
                         <td>ข้อมูลที่ต้องการแก้ไข</td>
                         <td><textarea name="comment" rows="10" cols="30"class='form-control' />ข้อมูลที่ต้องการแก้ไข</textarea></td>
                     </tr>
                     <tr>
                         <td></td>
                         <td>  <input type="hidden"name="_id" value="<?php echo $_id;?>">
                             <input type='submit' value='Save' class='btn btn-primary' />

                         </td>
                     </tr>
                 </table>
             </form>

            </h3>
          </a>
        </div>
        <hr>
        <div class="post-preview">
          <a href="post.html">
            <h2 class="post-title">
              กิจกรรมงานเลี้ยงรุ่นปี 2562
            </h2>
          </a>
          <p class="post-meta">Posted by
            <a href="#">NP</a>
            on September 18, 2019</p>
        </div>
        <hr>

        <!-- Pager -->
        <div class="clearfix">
          <a class="btn btn-primary float-right" href="#">Older Posts &rarr;</a>
        </div>
      </div>
    </div>
  </div>

  <hr>

    <div class="page-header">
      <h1>รายการแจ้งแก้ไขข้อมูล</h1>
    </div>
    <div class="row">
      <div class="col-sm-4">
        <div class="panel panel-primary">
          <div class="panel-heading">

          </div>
          <div class="panel-body">
            <?php
            $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/comment?apiKey='.MLAB_API_KEY);
            $data = json_decode($json);
            $isData=sizeof($data);
            if($isData >0){

                echo "<table class='table table-hover table-responsive table-bordered'>";//start table
              //creating our table heading
              echo "<tr>";
                  echo "<th>ลำดับ</th>";
                  echo "<th>name</th>";
                  echo "<th>Comment</th>";
                  echo "<th>Status</th>";
              echo "</tr>";

              // retrieve our table contents
            $id=0;
            foreach($data as $rec){
            $id++;
                           $_id=$rec->_id;

            foreach($_id as $rec_id){
              $_id=$rec_id;

            }
            $name=$rec->name;
              $comment=$rec->comment;
              $status=$rec->status;


              // creating new table row per record
              echo "<tr>";
                  echo "<td>{$id}</td>";
                  echo "<td>{$name}</td>";
                  echo "<td>{$comment}</td>";
                  echo "<td>{$status}</td>";
              echo "</tr>";
            }

            // end table
            echo "</table>";

            }// if no records found
            else{
              echo "<div align='center' class='alert alert-danger'>ยังไม่มี Comment ค่ะ</div>";
            }

                   ?>
          </div>
        </div>
      </div><!-- /.col-sm-4 -->
    </div><!-- row -->

            <div class="page-header">
              <h1>สรุปผลงานคณะกรรมการรุ่น</h1>
            </div>
            <div class="row">
              <div class="col-sm-4">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">การช่วยเหลือสวัสดิการ</h3>
                  </div>
                  <div class="panel-body">
                    รายละเอียดกิจกรรมการช่วยเหลือสวัสดิการ
                  </div>
                </div>
                <div class="panel panel-primary">
                  <div class="panel-heading">
                    <h3 class="panel-title">กิจกรรมพบปะสังสรรค์</h3>
                  </div>
                  <div class="panel-body">
                    รายละเอียดกิจกรรมพบปะสังสรรค
                  </div>
                </div>
              </div><!-- /.col-sm-4 -->
              <div class="col-sm-4">
                <div class="panel panel-success">
                  <div class="panel-heading">
                    <h3 class="panel-title">กิจกรรมเพื่อสาธารณะกุศล</h3>
                  </div>
                  <div class="panel-body">
                    รายละเอียดกิจกรรมเพื่อสาธารณะกุศล
                  </div>
                </div>
                <div class="panel panel-info">
                  <div class="panel-heading">
                    <h3 class="panel-title">ผลการปฏิบัติงานของเพื่อน</h3>
                  </div>
                  <div class="panel-body">
                    รายละเอียดผลการปฏิบัติงานของเพื่อนที่สำคัญ
                  </div>
                </div>
              </div><!-- /.col-sm-4 -->
              <div class="col-sm-4">
                <div class="panel panel-warning">
                  <div class="panel-heading">
                    <h3 class="panel-title">ประชาสัมพันธ์</h3>
                  </div>
                  <div class="panel-body">
                    ข่าวประชาสัมพันธ์
                  </div>
                </div>
                <div class="panel panel-danger">
                  <div class="panel-heading">
                    <h3 class="panel-title">ข้อมูลสมาชิก</h3>
                  </div>
                  <div class="panel-body">
                    ข้อมูลเพื่อนๆ สมาชิกในรุ่น (ต้องเข้าระบบด้วยรหัสผ่าน)
                  </div>
                </div>
              </div><!-- /.col-sm-4 -->
            </div>

              </div> <!-- /container -->

              <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

            <!-- Footer -->
            <footer>
              <div class="container">
                <div class="row">
                  <div class="col-lg-8 col-md-10 mx-auto">
                    <ul class="list-inline text-center">
                      <li class="list-inline-item">
                        <a href="#">
                          <span class="fa-stack fa-lg">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fab fa-twitter fa-stack-1x fa-inverse"></i>
                          </span>
                        </a>
                      </li>
                      <li class="list-inline-item">
                        <a href="#">
                          <span class="fa-stack fa-lg">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fab fa-facebook-f fa-stack-1x fa-inverse"></i>
                          </span>
                        </a>
                      </li>
                      <li class="list-inline-item">
                        <a href="#">
                          <span class="fa-stack fa-lg">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fab fa-github fa-stack-1x fa-inverse"></i>
                          </span>
                        </a>
                      </li>
                    </ul>
                    <p class="copyright text-muted">Copyright &copy; Your Website 2019</p>
                  </div>
                </div>
              </div>
            </footer>
</body>

</html>
