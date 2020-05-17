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
<!DOCTYPE HTML>
<html>
<head>
    <title>สมาชิก เตรียมทหาร 40 จปร.51</title>
     
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
         
    <!-- custom css -->
    <style>
    .m-r-1em{ margin-right:1em; }
    .m-b-1em{ margin-bottom:1em; }
    .m-l-1em{ margin-left:1em; }
    .mt0{ margin-top:0; }
    </style>
 
</head>
<body>
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
            echo "<a href='$del_url'>แจ้งแก้ไขข้อมูล</a>";
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
