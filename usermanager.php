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

   <div class="container">

        <div class="page-header">
		 <h1>ค้นหาตาม Username </h1>
        </div>
         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Username<input type='text' name='username' class='form-control' /></td>
            <td><input type='hidden' name='form_no' value='search_username'><input type='submit' value='ค้นหา' class='btn btn-primary' /></td>
        </tr>
    </table>
</form>
<div class="page-header">
<h1>ค้นหาตามชื่อ </h1>
</div>
 <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<table class='table table-hover table-responsive table-bordered'>
<tr>
    <td>ชื่อ<input type='text' name='name' class='form-control' /></td>
    <td><input type='hidden' name='form_no' value='search_name'><input type='submit' value='ค้นหา' class='btn btn-primary' /></td>
</tr>
</table>
</form>
	    <!-- PHP code to read records will be here -->
         <?php
 $message = isset($_GET['message']) ? $_GET['message'] : "";   echo $message;

	   if(isset($_POST['form_no'])){
       switch ($_POST['form_no']){
         case "search_name" :
         echo "search_name";
         $name=$_POST['name'];
        $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/crma51Phonebook?apiKey='.MLAB_API_KEY.'&q={"name":{"$regex":"'.$name.'"}}');
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
         break;
         case "search_username" : echo "search_username"; break;
         default : echo "no";
       }


  }// if no records found
else{
    echo "<div align='center' class='alert alert-danger'>ยังไม่มีข้อมูลค่ะ</div>";
}
	   }//end if isset _POST['name']
         ?>
    </div> <!-- end .container -->



<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
