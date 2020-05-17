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
    <title>ระบบค้นหาสมาชิก เตรียมทหาร 40 จปร.51</title>
      
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
          
</head>
<body>
  
   <div class="container">
  
        <div class="page-header">
		<table><tr><td></td><td> <h1>ค้นหาตามชื่อ </h1></td></tr></table>
        </div>
     <a href='search.php' class='btn btn-primary m-r-1em'>ค้นหา</a>
	    <a href='newMember.php' class='btn btn-primary m-r-1em'>เพิ่มสมาชิก</a>
	    <a href='listMember.php' class='btn btn-primary m-r-1em'>รายชื่อสมาชิกทั้งหมด</a>
	    <a href='logout.php' class='btn btn-danger'>Logout</a>
         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>ชื่อ <input type='text' name='name' class='form-control' /></td>
            <td><input type='submit' value='ค้นหา' class='btn btn-primary' /></td>
        </tr>
    </table>
</form>
	    
	    <!-- PHP code to read records will be here -->
         <?php
 $message = isset($_GET['message']) ? $_GET['message'] : "";
	    echo $message;
	   
	   if(isset($_POST['name'])){
 $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/crma51Phonebook?q={"name":"$name"}&apiKey='.MLAB_API_KEY);
 $data = json_decode($json);
 $isData=sizeof($data);
  if($isData >0){
      
      echo "<table class='table table-hover table-responsive table-bordered'>";//start table
    //creating our table heading
    echo "<tr>";
        echo "<th>ลำดับ</th>";
        echo "<th>ยศ ชื่อ สกุล</th>";
        echo "<th>ตำแหน่ง</th>";
        echo "<th>Email</th>";
        echo "<th>Tel.</th>";
        echo "<th>ID LINE</th>";
        echo "<th>Action</th>";
    echo "</tr>";
     
    // retrieve our table contents
$id=0;
foreach($data as $rec){
	$id++;
                 $_id=$rec->_id;
	
	foreach($_id as $rec_id){
		$_id=$rec_id;
		
	}
  $rank=$rec->rank;$name=$rec->name;$lastname=$rec->lastname;
		$position=$rec->position;
		$Email=$rec->Email;
		$Tel1=$rec->Tel1;
		$LineID=$rec->LineID;
			
     
    // creating new table row per record
    echo "<tr>";
        echo "<td>{$id}</td>";
        echo "<td>{$rank}{$name}{$lastname}</td>";
        echo "<td>{$position}</td>";
        echo "<td>{$Email}</td>";
        echo "<td>{$Tel1}</td>";
        echo "<td>{$LineID}</td>";
        echo "<td>";
            // we will use this links on next part of this post
	$comment_url="comment.php?id=".$_id;
            echo "<a href='$comment_url'>Delete</a>";
        echo "</td>";
    echo "</tr>";
}
 
// end table
echo "</table>";

  }// if no records found
else{
    echo "<div align='center' class='alert alert-danger'>ยังไม่มีข้อมูลค่ะ</div>";
}
	   }//end if isset _POST['name']
         ?>
    </div> <!-- end .container -->
	
	<div>
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
    </div> <!-- end .container -->
    
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
   
<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
</body>
</html>
