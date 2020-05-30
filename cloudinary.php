<?php
// Include config file
require_once "config.php";
require_once "vendor/autoload.php";
require_once "vendor/settings.php";
require_once "vendor/function.php";

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

//  \Cloudinary\Uploader::upload($file_tmp,array('$name'->$slug))
}
?>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
      <table class="table table-sm table-hover table-striped" width="300">
     <tr><td colspan="2">แนบรูปภาพ<input type='file' name='record_image' class='form-control' /></td></tr>
           <tr><td colspan="2" align="center"><input type="text"name="name" required="" placeholder="name"><input type="text"name="name" required="" placeholder="slug">
                <input type='submit' value='Submit' class='btn btn-primary' /></td></tr>
           </table>
       </form>

       <?php echo cl_image_tag('sample');?>
