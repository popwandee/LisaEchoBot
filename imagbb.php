<?php
// Initialize the session
session_start();
/*
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
if(!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin"){
    header("location: index.php?message=You are not admin.");
    exit;
}
*/
// Include config file
require_once "config.php";
require_once "vendor/autoload.php";
require_once "vendor/settings.php";
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
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
      	<input type="file" name="record_image" class="form-control" accept="image/*">
      	<input type="text" name="filename" class="form-control">
      	<button type="submit">Upload</button>
      </form>
    <?php
      if (!empty($_FILES['record_image'])) {
        echo "\n We got image ";print_r($_FILES['record_image']);
        $return = save_record_image($_FILES['record_image'],'test');
        $imgbb_url = $return['data']['url'];
        echo $imgbb_url;
        //insert_imgbb($imgbb_url);
      }
       ?>

       <?php
       function save_record_image($image,$name = null){
         $IMGBB_API_KEY = '6c23a11220bb2c1f7b9406175f3b8cbc';
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, 'https://api.imgbb.com/1/upload?key='.$IMGBB_API_KEY);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
         $extension = pathinfo($image['name'],PATHINFO_EXTENSION);
         $file_name = ($name)? $name.'.'.$extension : $image['name'] ;
         $data = array('image' => base64_encode(file_get_contents($image['tmp_name'])), 'name' => $file_name);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
         $result = curl_exec($ch);
         if (curl_errno($ch)) {
             return 'Error:' . curl_error($ch);
         }else{
           return json_decode($result, true);
           $newData = json_encode(array(
           	'fle_name' => $file_name
           	'file_url' => $file_url) );
           $opts = array('http' => array( 'method' => "POST",
                                          'header' => "Content-type: application/json",
                                          'content' => $newData
                                                      )
                                                   );
           $url = 'https://api.mlab.com/api/1/databases/crma51/collections/img?apiKey='.MLAB_API_KEY;
           $context = stream_context_create($opts);
           $returnValue = file_get_contents($url,false,$context);
         }
         curl_close($ch);
       }


       ?>
</div><!-- jumbotron-->
</div><!-- container theme-showcase-->
 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
 <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

 <!-- Latest compiled and minified Bootstrap JavaScript -->
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
