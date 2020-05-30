<?php
// Include config file
require "config.php";
require "vendor/autoload.php";
require "vendor/settings.php";
require "vendor/function.php";

?>
<button id="upload_widget" class="cloudinary-button">Upload files</button>
<script> cloudinary.setCloudName("dly6ftryr");</script>
<script src="https://widget.cloudinary.com/v2.0/global/all.js" type="text/javascript"></script>

<script type="text/javascript">
var myWidget = cloudinary.createUploadWidget({
  cloudName: 'dly6ftryr',
  uploadPreset: 'my_preset'}, (error, result) => {
    if (!error && result && result.event === "success") {
      console.log('Done! Here is the image info: ', result.info);
    }
  }
)

document.getElementById("upload_widget").addEventListener("click", function(){
    myWidget.open();
  }, false);
</script>
<?php
if(isset($_POST['submit'])){
  $name = $_POST['name'];
  $slug = $_POST['slug'];
  $file_name = $_FILES['file']['name'];
  $file_tmp = $_FILES['file']['tmp_name'];

  \Cloudinary\Uploader::upload($file_tmp,array($name=>$slug));
}
?>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
      <table class="table table-sm table-hover table-striped" width="300">
     <tr><td colspan="2">แนบรูปภาพ<input type='file' name='file' class='form-control' /></td></tr>
           <tr><td colspan="2" align="center"><input type="text"name="name" required="" placeholder="name"><input type="text"name="name" required="" placeholder="slug">
                <input type='submit' value='Submit' class='btn btn-primary' /></td></tr>
           </table>
       </form>
<img src="https://res.cloudinary.com/dly6ftryr/image/upload/w_1000,c_fill,ar_1:1,g_auto,r_max,bo_5px_solid_red,b_rgb:262c35/v1590735946/95736235_157455389101365_4497114901063401472_o_cquej7.jpg">
       <?php echo cl_image_tag('img_crma51');?>
