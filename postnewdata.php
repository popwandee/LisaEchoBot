<?php
// Initialize the session
session_start();

$message = isset($_SESSION['message'])? $_SESSION['message']: "";

// Cloudinary
require 'vendor/cloudinary/cloudinary_php/src/Cloudinary.php';
require 'vendor/cloudinary/cloudinary_php/src/Uploader.php';
require 'vendor/cloudinary/cloudinary_php/src/Api.php';

// Include config file
require_once "config.php";// mlab
require_once "vendor/autoload.php";
require_once "vendor/restdbclass.php";

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Relax Website">
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

  // ตรวจสอบ Action จาก _GET หรือ _POST
if(isset($_GET['action'])){         $action = $_GET['action'];
    }elseif(isset($_POST['action'])){   $action = $_POST['action'];
    }else{                              $action = "";
     }

$postform = isset($_POST['postform']) ? $_POST['postform'] : "";

?>
<div class="container theme-showcase" role="main">
    <div class="jumbotron">
            <h1>CRMA51</h1><p>แจ้งแก้ไข/เพิ่มข้อมูล</p>
<?php // core logic

echo $message;$_SESSION['message']="";

if($action=='newpost') {

    echo "insert new post to db<br>";

    if($postform=="image"){

        echo "get post from image form<br>";

        if (!empty($_FILES['upload_image'])) { //record_image

          $index=0;

          foreach ($_FILES["upload_image"]['tmp_name'] as $files){

            $target_file = basename($_FILES["upload_image"]["name"][$index]);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            if(!empty($imageFileType)){
              $public_id =$today."-".$index;
                $option=array("folder" => "post","public_id" => $public_id);
              $file_name = $img_url ="post/$public_id.".$imageFileType;
              $img_index = 'img_url-'.$index;
              $newData->$img_index=$file_name;
              $cloudUpload = \Cloudinary\Uploader::upload($files,$option);
              $index++;
            }

          }

        }// end if !empty _FILES
    }elseif($postform=="people"){

        $newData = array();
        $newData['rank']= isset($_POST['rank']) ? htmlspecialchars($_POST['rank']) : '';
        $newData['name']= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
        $newData['lastname']= isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : '';
        $newData['today'] = date("Ymd-His");
        $newData['telephone']= isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) :"$name-$today";
        $newData['position']= isset($_POST['position']) ? htmlspecialchars($_POST['position']) : '';
        $newData['organization']= isset($_POST['organization']) ? htmlspecialchars($_POST['organization']) : '';
        $newData['province']= isset($_POST['province']) ? htmlspecialchars($_POST['province']) : '';

             if (!empty($_FILES['upload_image'])) { //record_image
               foreach ($_FILES["upload_image"]['tmp_name'] as $files){
                 $target_file = basename($_FILES["upload_image"]["name"]);
                 $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                 if(!empty($imageFileType)){
                   $public_id =$telephone;
                   $option=array("folder" => "crma51","public_id" => $public_id);
                   $newData['img_url'] ="$public_id.".$imageFileType;
                   $cloudUpload = \Cloudinary\Uploader::upload($files,$option);
                   echo "<br> Upload result ->";print_r($cloudUpload);
                 }

               }

             }// end if !empty _FILES
                else{
                    $newData['img_url'] ="";
                }

          $result = insert_post($newData);

    }// end if post people

  }elseif($action=='showupdateform'){

      echo "Show Update Form<br>";

      $updateid = isset($_GET['updateid'])?$_GET['updateid']:"NO id";
      echo "ID is ".$updateid."<br>";

       update_form($updateid);

  }elseif($action=='updatepeople'){

      echo "Update DATA in DB<br>";

      $objectId = isset($_POST['_id']) ? $_POST['_id'] : "";
      $collectionName = "friend";
      $rank = isset($_POST['rank']) ? $_POST['rank'] : "";
      $name = isset($_POST['name']) ? $_POST['name'] : "";
      $lastname = isset($_POST['lastname']) ? $_POST['lastname'] : "";
      $telephone = isset($_POST['telephone']) ? $_POST['telephone'] : "";
      $position = isset($_POST['position']) ? $_POST['position'] : "";
      $organization = isset($_POST['organization']) ? $_POST['organization'] : "";
      $province = isset($_POST['province']) ? $_POST['province'] : "";
      $obj =  array(  "rank" => $rank,
      "name" => $name,
      "lastname" => $lastname,
      "telephone" => $telephone,
                      "position" => $position,
                      "organization" => $organization,
                      "province" => $province
                      );
      echo "\n obj is "; print_r($obj);
      $updateman = new RestDB;
      $res = $updateman->updateDocument($collectionName, $objectId, $obj);

      if($res){
          echo "Update complete<br>";

      }else{
          echo "Can not update data.<br>";
      }

  }elseif($action=='shownewpostform'){

      echo "Show new post form<br>";

          new_post_form();
  }

?>

</div><!-- jumbotron-->
</div><!-- container theme-showcase-->

<?php
function new_post_form(){ ?>
        <!-- form new people -->
    <div class="card bg-success px-md-5 border" align="center" style="max-width: 120rem;">
        <div class="card border-success md-12" style="max-width: 100rem;">
        <div class="card-body" align="left">
        <p class="card-text">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <input type="hidden"name="action" value="newpost">
                <input type="hidden"name="postform" value="people">
                <label class="col-sm-6 col-form-label">ข้อมูลบุคคล</label>
                <div class="form-group row">
                <label class="col-sm-6 col-form-label">ยศ ชื่อ สกุล</label>
                    <div class="form-group col-md-2">
                        <input class="form-control" id="rank" name="rank" type="text" placeholder="ยศ">
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" id="name" name="name" type="text" placeholder="ชื่อ">
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" id="lastname" name="lastname" type="text" placeholder="นามสกุล">
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" id="nickname" name="nickname" type="text" placeholder="ชื่อเล่น">
                    </div>
                </div>
                <div class="form-group row">
                <label class="col-sm-6 col-form-label" for="position">การติดต่อ</label>
                    <div class="form-group col-md-2">
                        <input class="form-control" id="telephone" name="telephone" type="text" placeholder="099 999 9999">
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" id="position" name="position" type="text" placeholder="ตำแหน่ง">
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" id="organization" name="organization" type="text" placeholder="หน่วย กองพล กองทัพ">
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" id="province" name="province" type="text" placeholder="จังหวัด">
                    </div>
                </div>
                <div class="form-group row">
                <label class="col-sm-6 col-form-label" for="upload_image">รูปโปรไฟล์</label>
                    <div class="form-group col-md-6">
                        <input class="form-control" name="upload_image" type="file">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" name="submit" class="btn btn-info">Submit</button>
                    </div>
                </div>
            </form>
    </div>
    </div>
    </div>
    <br>
    <hr>
    <!-- form upload image -->
    <div class="card bg-info px-md-5 border" align="center" style="max-width: 120rem;">
        <div class="card border-success md-12" style="max-width: 100rem;">
            <div class="card-body" align="left">
                <p class="card-text">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <input type="hidden"name="action" value="newpost">
                        <input type="hidden"name="postform" value="image">
                        <label class="col-sm-6 col-form-label">รูปภาพ</label>
                        <div class="form-group row">
                        <label class="col-sm-6 col-form-label">ชื่อรูปภาพ</label>
                            <div class="form-group col-md-4">
                                <input class="form-control" id="title" name="title" type="text">
                            </div>
                        </div><div class="form-group row">
                            <div class="form-group col-md-4">
                                <input class="form-control" name="record_image[]" type="file">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group col-md-4">
                                <input class="form-control" name="record_image[]" type="file">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group col-md-4">
                                <input class="form-control" name="record_image[]" type="file">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group col-md-4">
                                <input class="form-control" name="record_image[]" type="file">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group col-md-4">
                                <input class="form-control" name="record_image[]" type="file">
                            </div>
                        </div>
                        <div class="form-group row">
                        <label for="tag" class="col-sm-6 col-form-label">Tag</label>
                            <div class="form-group col-md-4">
                                <input class="form-control" id="tag" name="tag" type="text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <button type="submit" name="submit" class="btn btn-info">Submit</button>
                            </div>
                        </div>
                    </form>
                        </p>
                </div>
              </div>    <!-- end of card div -->
</div>

<?php } // end request_form ?>


<?php function insert_post($newData){

    $rank = $newData['rank'];
    $name = $newData['name'];
    $lastname= $newData['lastname'];
    $telephone = $newData['telephone'];
    $position = $newData['position'];
    $organization = $newData['organization'];
    $province = $newData['province'];
    $img_url = $newData['img_url'];
    // นำข้อมูลเข้าเก็บในฐานข้อมูล
    $collectionName = "friend";
    $obj =   '{"rank":"'.$rank.'","name":"'.$name.'","lastname":"'.$lastname.'", "telephone":"'.$telephone.'","position":"'.$position.'",
        "organization":"'.$organization.'", "province":"'.$province.'", "img_url":"'.$img_url.'"}';

    $newman = new RestDB();
    $returnValue = $newman->insertDocument($collectionName,$obj);
      if($returnValue){
     $message= "<div align='center' class='alert alert-success'>เพิ่มข้อมูล ".$rank.$name." ".$lastname." เรียบร้อย</div>";
        }else{
     $message= "<div align='center' class='alert alert-danger'>ไม่สามารถเพิ่มข้อมูลได้ โปรดติดต่อผู้ดูแลระบบ</div>";
               }
    $_SESSION["message"]=$message;

      return $message;
}//end function insert_request
 ?>
<?php function update_form($id){ ?>
<?php
echo "Inside update Form<br>";
     $collectionName = "friend";
     $obj = '{"_id":"'.$id.'"}';
     $sort= '';
     $coupon = new RestDB();
     $res = $coupon->selectDocument($collectionName,$obj,$sort);
     if($res){
         foreach($res as $rec){
             print_r($rec);
     ?>
         <div class="card bg-info px-md-5 border" align="center" style="max-width: 120rem;">
         <div class="card border-success md-12" style="max-width: 100rem;">
         <div class="card-header"align="left">ข้อมูลบุคคล</div>
         <div class="card-body" align="left">
         <p class="card-text">
             <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                 <input type="hidden"name="action" value="updatepeople">
                 <input type="hidden"name="postform" value="people">
                 <input type="hidden"name="updateid" value="<?php echo $rec['_id'];?>">
                 <label class="col-sm-6 col-form-label">ข้อมูลบุคคล</label>
                 <div class="form-group row">
                 <label class="col-sm-6 col-form-label">ยศ ชื่อ สกุล</label>
                     <div class="form-group col-md-2">
                         <input class="form-control" id="rank" name="rank" type="text" value="<?php echo $rec['rank'];?>" placeholder="ยศ">
                     </div>
                     <div class="form-group col-md-4">
                         <input class="form-control" id="name" name="name" type="text" value="<?php echo $rec['name'];?>" placeholder="ชื่อ">
                     </div>
                     <div class="form-group col-md-4">
                         <input class="form-control" id="lastname" name="lastname" type="text" value="<?php echo $rec['lastname'];?>" placeholder="นามสกุล">
                     </div>
                     <div class="form-group col-md-4">
                         <input class="form-control" id="nickname" name="nickname" type="text" value="<?php echo $rec['nickname'];?>" placeholder="ชื่อเล่น">
                     </div>
                 </div>
                 <div class="form-group row">
                 <label class="col-sm-6 col-form-label" for="position">การทำงาน</label>
                     <div class="form-group col-md-4">
                         <input class="form-control" id="telephone" name="telephone" type="text" value="<?php echo $rec['telephone'];?>">
                     </div>
                     <div class="form-group col-md-4">
                         <input class="form-control" id="position" name="position" type="text" value="<?php echo $rec['position'];?>" placeholder="ตำแหน่ง">
                     </div>
                     <div class="form-group col-md-4">
                         <input class="form-control" id="organization" name="organization" type="text" value="<?php echo $rec['organization'];?>" placeholder="หน่วย กองพล กองทัพ">
                     </div>
                     <div class="form-group col-md-4">
                         <input class="form-control" id="province" name="province" type="text" value="<?php echo $rec['province'];?>" placeholder="จังหวัด">
                     </div>
                 </div>
                 <div class="form-group row">
                 <label class="col-sm-6 col-form-label" for="position">รูปโปรไฟล์</label>
                     <div class="form-group col-md-6">
                         <input class="form-control" name="upload_image" type="file">
                     </div>
                 </div>
                 <div class="form-group row">
                     <div class="col-sm-10">
                         <button type="submit" name="submit" class="btn btn-info">Submit</button>
                     </div>
                 </div>
             </form>
     </div>
     </div>
 </div>
 <?php
} // end foreach
} // end if select result
else{
    echo "No Data from DB to edit<br>";
}
 } // end update_form ?>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
