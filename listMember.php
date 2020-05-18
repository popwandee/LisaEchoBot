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
  <!-- Fixed navbar -->
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">AFAPS40 - CRMA51</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li class="active"><a href="index.php">Home</a></li>
          <li><a href="signup.php">ลงทะเบียน</a></li>
          <li><a href="listMember.php">รายชื่อเพื่อน</a></li>
          <li><a href="logout.php">ออกจากระบบ</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="girl.php">ภาพสวยๆ</a></li>
              <li><a href="https://www.facebook.com">เฟสบุ๊กรุ่น</a></li>
              <li role="separator" class="divider"></li>
              <li class="dropdown-header">เว็บไซต์หน่วย(เพื่อนเป็น ผบ.หน่วย)</li>
              <li><a href="https://www.facebook.com/%E0%B8%81%E0%B8%AD%E0%B8%87%E0%B8%9E%E0%B8%B1%E0%B8%99%E0%B8%97%E0%B8%AB%E0%B8%B2%E0%B8%A3%E0%B8%A3%E0%B8%B2%E0%B8%9A%E0%B8%97%E0%B8%B5%E0%B9%88-%E0%B9%91-%E0%B8%81%E0%B8%A3%E0%B8%A1%E0%B8%97%E0%B8%AB%E0%B8%B2%E0%B8%A3%E0%B8%A3%E0%B8%B2%E0%B8%9A%E0%B8%97%E0%B8%B5%E0%B9%88-%E0%B9%96-1929904703887679">ร.6 พัน.1</a></li>
              <li><a href="https://www.facebook.com/chirakid.chidpukdee">ร.16 พัน.1</a></li>
              <li><a href="#">ร.</a></li>
            </ul>
          </li>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </nav>

  <div class="container theme-showcase" role="main">
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <h1>AFAPS40 - CRMA51</h1>
      <p>เว็บไซต์ เตรียมทหาร รุ่นที่ 40 จปร.รุ่นที่ 51</p>
    </div>

    <div class="page-header">
      <h1>สมาชิก เตรียมทหาร40 จปร.51</h1>
    </div>
    <div class="well">
      <p>นักเรียนเตรียมทหารรุ่นที่ 40 ได้รายงานตัวเข้ารับการศึกษา ณ โรงเรียนเตรียมทหาร ถนนพระรามสี่ กทม. เมื่อ พ.ศ.2540 จำนวนทั้งสิ้น  นาย
      สำเร็จการศึกษาจากโรงเรียนเตรียมทหาร เข้ารับการศึกษา โรงเรียนนายร้อยพระจุลจอมเกล้า รุ่นที่ 51 จำนวน  นาย
    สำเร็จรับราชการเมื่อ 26 ธ.ค.2546 จำนวน นาย และสำเร็จการศึกษาเข้ารับราชการ เมื่อ ธ.ค.2547 จำนวน  นาย
  เข้ารับการศึกษาหลักสูตรหลักประจำ โรงเรียนเสนาธิการทหารบก ชุดที่ 92 จำนวน นาย
ปัจจุบันปฏิบัติหน้าที่ตาม ตารางต่อไปนี้ (หากข้อมูลไม่ถูกต้อง ไม่ทันสมัย กรุณาคลิกแก้ไข และให้ข้อมูลกับคณะกรรมการรุ่นด้วย จักขอบคุณมากครับ)</p>
    </div>


 <?php
$tz_object = new DateTimeZone('Asia/Bangkok');
         $datetime = new DateTime();
         $datetime->setTimezone($tz_object);
         $dateTimeToday = $datetime->format('Y-m-d');

	?>

    <!-- container -->
    <div class="container">

        <div class="page-header">
		<table><tr><td></td><td> <h1>วันที่ <?php echo $dateTimeToday;?> </h1></td></tr></table>
        </div>
     <a href='search.php' class='btn btn-primary m-r-1em'>ค้นหา</a>
	    <a href='newMember.php' class='btn btn-primary m-r-1em'>เพิ่มข้อมูลสมาชิก</a>
	    <a href='logout.php' class='btn btn-danger'>Logout</a>

	    <!-- PHP code to read records will be here -->
         <?php
 $message = isset($_GET['message']) ? $_GET['message'] : "";
	    echo $message;
 $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/crma51Phonebook?apiKey='.MLAB_API_KEY);
 $data = json_decode($json);
 $isData=sizeof($data);
  if($isData >0){
	        echo "<table class='table table-hover table-responsive table-bordered'>";//start table
    //creating our table heading
    echo "<tr>";
        echo "<th>ลำดับ</th>";
        echo "<th>ยศ ชื่อ สกุล</th>";
        echo "<th>ตำแหน่ง</th>";
        echo "<th>อีเมล์</th>";
        echo "<th>โทรศัพท์</th>";
        echo "<th>Action</th>";
    echo "</tr>";
    // retrieve our table contents
$id=0;
foreach($data as $rec){
	$id++;
                 $_id=$rec->_id;

	foreach($_id as $rec_id){
		$_id=$rec_id;
	} // end foreach id as rec_id

	        $rank = $rec->rank;
		$name = $rec->name;
		$lastname = $rec->lastname;
		$position = $rec->position;
		$Email = $rec->Email;
		$Tel1 = $rec->Tel1;

	    // creating new table row per record
    echo "<tr>";
        echo "<td>{$id}</td>";
        echo "<td>{$rank} {$name} {$lastname}</td>";
        echo "<td>{$position}</td>";
        echo "<td>{$Email}</td>";
        echo "<td>{$Tel1}</td>";
        echo "<td>";
            // we will use this links on next part of this post
	$del_url="comment.php?id=".$_id;
            echo "<a href='$del_url'>แก้ไข</a>";
        echo "</td>";
    echo "</tr>";

}// end foreach data as rec
	  // end table
echo "</table>";
  }// if no records found
else{
    echo "<div align='center' class='alert alert-danger'>ยังไม่มีข้อมูล</div>";
}
         ?>
    </div> <!-- end .container -->


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
