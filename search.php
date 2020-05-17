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
  
    <!-- container -->
    <div class="container">
   
      
      
    <form action="newMember.php" method="post">
    <table class='table table-hover table-responsive table-bordered'>
        
        <tr>
            <td>ชื่อ นามสกุล</td>
            <td><input type='text' name='name' class='form-control' /></td>
            <td><input type='text' name='lastname' class='form-control' /></td>
        </tr>
        <tr>
            
            <td colspan="3">
                <input type='submit' value='Search' class='btn btn-primary' />
            </td>
        </tr>
     <tr><td colspan="3">
  <?php  if(isset($_SESSION["message"])){ echo $_SESSION["message"]; $_SESSION["message"]="";} ?> 
      </td></tr>
    </table>
</form>
          
    </div> <!-- end .container -->
    
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
   
<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
</body>
</html>
