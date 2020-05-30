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
<?php
require __DIR__ .'/lib/rb.php';
if (is_file(__DIR__ . 'vendor/cloudinary/cloudinary_php/autoload.php') && is_readable(__DIR__ . 'vendor/cloudinary/cloudinary_php/autoload.php')) {
    require_once __DIR__.'vendor/cloudinary/cloudinary_php/autoload.php';
} else {
    // Fallback to legacy autoloader
    require_once __DIR__.'vendor/cloudinary/cloudinary_php/autoload.php';
    require_once __DIR__.'vendor/cloudinary/cloudinary_php/src/Helpers.php';
}

error_reporting(E_ALL | E_STRICT);

// Sets up Cloudinary's parameters and RB's DB
include 'vendor/settings.php';

function create_photo($file_path, $orig_name)
{
    # Upload the received image file to Cloudinary
    $result = \Cloudinary\Uploader::upload($file_path, array(
            "tags" => "backend_photo_album",
            "public_id" => $orig_name,
    ));

    unlink($file_path);
    return $result;
}
if(isset($_FILES["files"])){
  $files = $_FILES["files"];
  $files = is_array($files) ? $files : array( $files );
  $files_data = array();
  foreach ($files["tmp_name"] as $index => $value) {
      array_push($files_data, create_photo($value, $files["name"][$index]));
  }
}

?>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
      <table class="table table-sm table-hover table-striped" width="300">
     <tr><td colspan="2">แนบรูปภาพ<input type='file' name='files[]' class='form-control' /></td></tr>
           <tr><td colspan="2" align="center"><input type="text"name="name" required="" placeholder="name"><input type="text"name="name" required="" placeholder="slug">
                <input type='submit' value='Submit' class='btn btn-primary' /></td></tr>
           </table>
       </form>

       <div id='backend_upload'>
           <h1>Upload through your server</h1>
           <form action="upload_backend.php" method="post" enctype="multipart/form-data">
               <input id="fileupload" type="file" name="files[]" multiple accept="image/gif, image/jpeg, image/png">
               <input type="submit" value="Upload">
           </form>
       </div>
</body>
</html>
