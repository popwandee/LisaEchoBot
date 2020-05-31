<?php
require 'vendor/cloudinary/cloudinary_php/src/Cloudinary.php';
require 'vendor/cloudinary/cloudinary_php/src/Uploader.php';
require 'vendor/cloudinary/cloudinary_php/src/Api.php';
require 'vendor/settings.php';


if (isset($_POST["submit"])) {
    print_r($_FILES["fileToUpload"]);
    $cloudUpload = \Cloudinary\Uploader::upload($_FILES["fileToUpload"]['tmp_name']);
    print_r($cloudUpload);
}

?>
<!DOCTYPE HTML>
  <html>
    <head>
      <!-- script for Cloudinaty -->
      <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js'></script>
      <script src='jquery.ui.widget.js' type='text/javascript'></script>
      <script src='jquery.iframe-transport.js' type='text/javascript'></script>
      <script src='jquery.fileupload.js' type='text/javascript'></script>
      <script src='jquery.cloudinary.js' type='text/javascript'></script>

  </head>

  <body>
    <?php echo cloudinary_js_config(); ?>
        <?php
          if (array_key_exists('REQUEST_SCHEME', $_SERVER)) {
            $cors_location = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] .
          dirname($_SERVER["SCRIPT_NAME"]) . "/cloudinary_cors.html";
          } else {
            $cors_location = "http://" . $_SERVER["HTTP_HOST"] . "/cloudinary_cors.html";
          }
      ?>

        <form action="uploaded.php" method="post">
          <?php echo cl_image_upload_tag('image_id', array("callback" => $cors_location)); ?>
        </form>

  </body>
</html>
