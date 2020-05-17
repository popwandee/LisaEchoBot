<?php
if($_POST){
        $name=htmlspecialchars(strip_tags($_POST['name']));
        $lastname=htmlspecialchars(strip_tags($_POST['lastname']));
        $position=htmlspecialchars(strip_tags($_POST['position']));
        $Tel1=htmlspecialchars(strip_tags($_POST['Tel1']));
        }
echo "NAME :".$name;
echo "Lastname :".$lastname;
echo "Position :".$position;
echo "Tel. :".$Tel1;
?>
