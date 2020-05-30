<?php
if(isset($_POST['submit'])){
  $name = $_POST['name'];
  $slug = $_POST['slug'];
  $file_name = $_FILES['file']['name'];
  $file_tmp = $_FILES['file']['tmp_name'];

  \Cloudinary\Uploader::upload($file_tmp,array($name=>$slug));
}
?>
<div class="card">
<label class="file-upload-container" for="file-upload">
<input id="file-upload" type="file" style="display:none;">
Select an Image
</label>
</div>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
      <table class="table table-sm table-hover table-striped" width="300">
     <tr><td colspan="2">แนบรูปภาพ<input type='file' name='file' class='form-control' /></td></tr>
           <tr><td colspan="2" align="center"><input type="text"name="name" required="" placeholder="name"><input type="text"name="name" required="" placeholder="slug">
                <input type='submit' value='Submit' class='btn btn-primary' /></td></tr>
           </table>
       </form>
       <?php echo cl_image_tag('img_crma51');?>

       <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
       <script src="app.js"></script>
