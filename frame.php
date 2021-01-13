<?php
session_start();

if(isset($_POST['reset_math_test'])){
    $no =  $_SESSION['no']= 1;
    $question = $_SESSION['question']= array();
    $score = $_SESSION['score']=0;
    $name = $_SESSION['name']="";
}else{
    $no = isset($_SESSION['no']) ? $_SESSION['no'] : 1;
    $question = isset($_SESSION['question']) ? $_SESSION['question'] : array();
    $score = isset($_SESSION['score']) ? $_SESSION['score'] : 0;
    $name = isset($_SESSION['name']) ? $_SESSION['name'] : "";
}

if(isset($_POST['name'])){
    $name = $_SESSION['name']=$_POST['name'];
}
?>
<br>
<form method="post" action="frame.php">
    ชื่อผู้เล่น<input type="text" name="name">
    <input type="submit" name="Submit" value="ตกลง">
</form>
<form method="post" action="frame.php">
    <input type="hidden" name="reset_math_test" value="true">
    <input type="submit" name="Submit" value="เริ่มใหม่">
</form>
<br>

<div align="center">
Hello สวัสดีคุณ <?php echo $name;?> เชิญเล่นเกมส์ตอบคำถามคณิตศาสตร์
<?php
$x = mt_rand(1,10);
$y = mt_rand(1,10);
$arr = array("+","-","x","/");
$case = array_rand($arr);
$method = $arr[$case];
?>
<form method="post" action="frame.php">
    <input type="hidden" name="math_test" value="true"><br>
    <input type="hidden" name="x" value="<?php echo $x;?>">
    <input type="hidden" name="method" value="<?php echo $method;?>">
    <input type="hidden" name="y" value="<?php echo $y;?>">
    <?php
    echo $x;
    echo "  ".$method."   ";
    echo $y;
    ?>
     = <input type="text" name="z" >
    <input type="submit" name="Submit" value="ตอบ">
</form>
<br>
<?php

if(isset($_POST['math_test'])){
    $x = isset($_POST['x']) ? $_POST['x'] : 0 ;
    $y = isset($_POST['y']) ? $_POST['y'] : 0 ;
    $z = isset($_POST['z']) ? $_POST['z'] : 0 ;
    $method = isset($_POST['method']) ? $_POST['method'] : 0 ;

    switch($method){
        case "+":
            $question[$no]['question']= $x." บวก ".$y." เท่ากับเท่าไหร่ ";
            $answer = $x + $y;
        break;
        case "-":
            $question[$no]['question']= $x." ลบ ".$y." เท่ากับเท่าไหร่ ";
            $answer = $x - $y;
        break;
        case "x":
            $question[$no]['question']= $x." คูณ ".$y." เท่ากับเท่าไหร่ ";
            $answer = $x * $y;
        break;
        case "/":
            $question[$no]['question']= $x." หาร ".$y." เท่ากับเท่าไหร่ ";
            $answer = $x / $y;

        break;
    }
    echo $question[$no]['question'];
    echo"คุณตอบว่า ".$z." ";

    $question[$no]['question']=$question[$no]['question']."= ".$z;
    if($z==$answer){
        echo "<font color=blue>ถูกต้องแล้วครับ</font>";
        $question[$no]['answer'] = "ถูกต้อง";
        $score = $score+1;
    }else{
        echo "<font color=red>คุณตอบผิด ลองดูใหม่นะครับ</font>";
        echo "คำตอบที่ถูกต้องคือ ".$answer." <br>";
        $question[$no]['answer'] = "ผิด";
    }
$no++; $_SESSION['no'] = $no;
$_SESSION['score'] = $score;
$_SESSION['question'] = $question;
}
if(!empty($question)){
    echo "<div align=left>ผลการเล่นเกมส์ของคุณ ".$name."<br>";
    if(!empty($score)){
        echo "<h2>คะแนนรวมของคุณ $name คือ <font color=blue>".$score."</font></h2>";
    }
    $i=1;
    foreach($question as $record){
        echo $i.". ".$record['question']." : ".$record['answer']." <br>";
        $i++;
    }

    echo "</div>";
}

?>
</div>
