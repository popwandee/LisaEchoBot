<html>
<head>
  <meta charset="utf-8">
  <title>Cloudinary</title>
  <link rel="stylesheet" href="https://cdnis.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
if(isset($_POST['submit'])){
  $name = $_POST['name'];
  $slug = $_POST['slug'];
  $file_name = $_FILES['file']['name'];
  $file_tmp = $_FILES['file']['tmp_name'];

  \Cloudinary\Uploader::upload($file_tmp,array('tag'=>$slug));
}
?>
<div class="card">
  <img src="https://res.cloudinary.com/dly6ftryr/image/upload/v1590754500/mjbmpyflc6vmqsfbmmgd.jpg" id="img-preview" />
</div>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
      <table class="table table-sm table-hover table-striped" width="300">
     <tr><td colspan="2">แนบรูปภาพ<input type='file' name='file' class='form-control' /></td></tr>
           <tr><td colspan="2" align="center"><input type="text"name="name" required="" placeholder="name"><input type="text"name="name" required="" placeholder="slug">
                <input type='submit' value='Submit' class='btn btn-primary' /></td></tr>
           </table>
       </form>
       <?php echo cl_image_tag('img_crma51');?>
</body>
</html>
