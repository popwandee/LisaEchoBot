<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "กรุณากรอกข้อมูล username.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "กรุณากรอกข้อมูล password.";
    } else{
        $password = trim($_POST["password"]);
        //$hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){

     // Set parameters
     $param_username = $username;

     // Check if username exists, if yes then verify password
     $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/manager?apiKey='.MLAB_API_KEY.'&q={"username":{"$regex":"'.$param_username.'"}}');
     $data = json_decode($json);
     $isData=sizeof($data);
     if($isData >0){
        // มีข้อมูลผู้ใช้อยู่
     foreach($data as $rec){
        $username=$rec->username;
        $approved=$rec->approved;
        $type=$rec->type;
        $hashed_password=$rec->password;
         }
       if(password_verify($password, $hashed_password)){
                            // Password is correct
        if($approved){
        //so start a new session

                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["username"] = $username;
                            $_SESSION["type"] = $type;

                            // Redirect user to welcome page
                            header("location: index.php");
        }else{
        // User not approved yet
         $username_err = "คุณยังไม่ได้รับอนุมัติให้เข้าระบบ กรุณาติดต่อ Admin";
        }
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "รหัสผ่านไม่ถูกต้อง";
                        }
      }else{
      $username_err = "ไม่มีข้อมูล Username นี้ครับ";
}


    }else{
     echo "กรุณากรอกข้อมูล Username และ Password";
    }


}
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

    </div> <!-- container theme-showcase -->

    <div class="page-header">
      <div class="panel panel-info">
        <div class="panel-heading">
            <h2>Login </h2>
            <p>กรุณากรอกข้อมูลเพื่อ login เข้าสู่ระบบระบบสมาชิก เตรียมทหาร40 จปร.51</p>
        </div>
        <div class="row">
          <div class="col-sm-4" >
        <div class="panel-body">


        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>

        </form>
      </div> <!-- panel-body -->
  </div> <!-- panel panel-primary-->
  </div> <!-- col-sm-4 -->
  </div> <!-- row -->

      </div> <!-- panel panel-info -->
        </div> <!-- page-header -->
</body>
</html>
