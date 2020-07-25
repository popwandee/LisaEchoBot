<?php // callback.php
// กรณีต้องการตรวจสอบการแจ้ง error ให้เปิด 3 บรรทัดล่างนี้ให้ทำงาน กรณีไม่ ให้ comment ปิดไป
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//require __DIR__."/vendor/autoload.php";

// Cloudinary
require 'vendor/cloudinary/cloudinary_php/src/Cloudinary.php';
require 'vendor/cloudinary/cloudinary_php/src/Uploader.php';
require 'vendor/cloudinary/cloudinary_php/src/Api.php';

// Include config file
require_once "config.php";// mlab
require_once "vendor/autoload.php";
require_once "vendor/function.php";
/*
define("MLAB_API_KEY", '');
define("LINE_MESSAGING_API_CHANNEL_SECRET", '');
define("LINE_MESSAGING_API_CHANNEL_TOKEN", '');
*/
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use \Statickidz\GoogleTranslate;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\Constant\Flex\ComponentLayout;
use LINE\LINEBot\Constant\Flex\ComponentIconSize;
use LINE\LINEBot\Constant\Flex\ComponentImageSize;
use LINE\LINEBot\Constant\Flex\ComponentImageAspectRatio;
use LINE\LINEBot\Constant\Flex\ComponentImageAspectMode;
use LINE\LINEBot\Constant\Flex\ComponentFontSize;
use LINE\LINEBot\Constant\Flex\ComponentFontWeight;
use LINE\LINEBot\Constant\Flex\ComponentMargin;
use LINE\LINEBot\Constant\Flex\ComponentSpacing;
use LINE\LINEBot\Constant\Flex\ComponentButtonStyle;
use LINE\LINEBot\Constant\Flex\ComponentButtonHeight;
use LINE\LINEBot\Constant\Flex\ComponentSpaceSize;
use LINE\LINEBot\Constant\Flex\ComponentGravity;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\RawMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder ;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ButtonComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\IconComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ImageComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\CarouselContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\SpacerComponentBuilder;
use LINE\LINEBot\QuickReplyBuilder\ButtonBuilder\QuickReplyButtonBuilder;
use LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder;
$logger = new Logger('LineBot');
$logger->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));
$bot = new \LINE\LINEBot(
    new \LINE\LINEBot\HTTPClient\CurlHTTPClient(LINE_MESSAGING_API_CHANNEL_TOKEN),
    ['channelSecret' => LINE_MESSAGING_API_CHANNEL_SECRET]
);

$signature = $_SERVER["HTTP_".\LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
try {
	$events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);
} catch(\LINE\LINEBot\Exception\InvalidSignatureException $e) {
	error_log('parseEventRequest failed. InvalidSignatureException => '.var_export($e, true));
} catch(\LINE\LINEBot\Exception\UnknownEventTypeException $e) {
	error_log('parseEventRequest failed. UnknownEventTypeException => '.var_export($e, true));
} catch(\LINE\LINEBot\Exception\UnknownMessageTypeException $e) {
	error_log('parseEventRequest failed. UnknownMessageTypeException => '.var_export($e, true));
} catch(\LINE\LINEBot\Exception\InvalidEventRequestException $e) {
	error_log('parseEventRequest failed. InvalidEventRequestException => '.var_export($e, true));
}
foreach ($events as $event) {
	// Message Event
 if ($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage) {
  $rawText = $event->getText();
  //$text = strtolower($rawText);
  $text = $rawText;
  $explodeText=explode(" ",$text);
  $textReplyMessage="";
	$log_note=$text;
	 $tz_object = new DateTimeZone('Asia/Bangkok');
         $datetime = new DateTime();
         $datetime->setTimezone($tz_object);
         $dateTimeNow = $datetime->format('Y\-m\-d\ H:i:s');
	$replyToken = $event->getReplyToken();
        $multiMessage =     new MultiMessageBuilder;
	$replyData='No Data';
  switch ($text[0]) {
    case '#':
        if($explodeText[0]!='#'){

        $find_word=substr($explodeText[0],1);
				 $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY.'&q={"$or":[{"name":{"$regex":"'.$find_word.'"}},{"nickname":{"$regex":"'.$find_word.'"}},{"lastname":{"$regex":"'.$find_word.'"}},{"province":{"$regex":"'.$find_word.'"}},{"detail":{"$regex":"'.$find_word.'"}},{"Tel1":{"$regex":"'.$find_word.'"}},{"position":{"$regex":"'.$find_word.'"}}]}');
                  $data = json_decode($json);
                  $isData=sizeof($data);
			            $count = 1;
                  if($isData >0){
                     foreach($data as $rec){
                    $textReplyMessage= $textReplyMessage.$count.' '.$rec->rank.$rec->name.' '.$rec->lastname.' ('.$rec->position.') โทร '.$rec->Tel1." ค่ะ\n\n";
				            $count++;
                    $img_url=$rec->img_url;
                    if(!empty($img_url)){
                    $img_url="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$rec->img_url;
                    $imageMessage = new ImageMessageBuilder($img_url,$img_url);
                    $multiMessage->add($imageMessage);
                  }// end if ! empty img_url
                    }//end for each
		                $textMessage = new TextMessageBuilder($textReplyMessage);
		                $multiMessage->add($textMessage);
                    $replyData = $multiMessage;
	               }else{
                      $noAnser = array("..พักบ้างนะค่ะ.. ","..พี่คิดว่าหนูจะรู้ไหมอ่ะค่ะ.. ","..พี่ธนูว่ายังไงดีค่ะ.. ","..พี่ถามลิซ่ารึเปล่าค่ะ.. ","..ลิซ่าไม่รู้ค่ะ.. ","ไม่มีใครสอนลิซ่าเรื่องนี้ค่ะ","..ลิซ่าจำไม่ได้ค่ะ..","..วันนี้อากาศดีนะค่ะ..","เอ่อ...ไม่มีข้อมูลค่ะ ลองถามใหม่ดีไหมค่ะ");
                      $count=count($noAnser);
                      $index = mt_rand(0,$count-1);
                      $textReplyMessage=$textReplyMessage.$noAnser[$index];
          				   $textMessage = new TextMessageBuilder($textReplyMessage);
                     $multiMessage->add($textMessage);
                     $replyData = $multiMessage;
	                  }
            }
            break;

  case '!':
      $indexCount=0;$answer='';
      foreach($explodeText as $rec){
      $indexCount++;
      if($indexCount>1){//น่าจะมีคำถามและคำตอบมาด้วย
        $answer= $answer." ".$explodeText[$indexCount];
        }
      }//end foreach $explodeText นับจำนวนคำ เพื่อตรวจสอบว่ามีคำถามและคำตอบมาด้วย
      if(($indexCount>1) && (!empty($explodeText[2]))){
        //Post New Data
        $newData = json_encode(array('question' => $explodeText[1],'answer'=> $answer) );
        $opts = array('http' => array( 'method' => "POST",
                      'header' => "Content-type: application/json",
                      'content' => $newData
                      ) );
         $url = 'https://api.mlab.com/api/1/databases/crma51/collections/km?apiKey='.MLAB_API_KEY.'';
         $context = stream_context_create($opts);
         $returnValue = file_get_contents($url,false,$context);
        if($returnValue){
          $textReplyMessage= $textReplyMessage."\nขอบคุณที่สอนน้อง Lisa ค่ะ";
          $textReplyMessage= $textReplyMessage."\nน้อง Lisa จำได้แล้ว ถ้าถาม ".$explodeText[1]." ให้ตอบว่า ".$answer;
          }else{
            $textReplyMessage= $textReplyMessage."\nน้อง Lisa ไม่เข้าใจค่ะ";
          }
        }// end if $indexCount>1 ->> insert database
        else{ // $indexCount>1
          $textReplyMessage= $textReplyMessage."\n ไม่มีคำตอบมาให้ด้วยเหรอค่ะ";
        }
           $textMessage = new TextMessageBuilder($textReplyMessage);
           $multiMessage->add($textMessage);
           $replyData = $multiMessage;
          break;
case '$':
  $text=substr($rawText,1);
 $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/gallery?apiKey='.MLAB_API_KEY.'&q={"title":{"$regex":"'.$text.'"}}');
          $data = json_decode($json);
          $isData=sizeof($data);
          if($isData >0){
             foreach($data as $rec){
               $img_index='img_url-0';$img_url=$rec->$img_index;
               if(!empty($img_url)){
               $img_url="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$rec->$img_index;
               $imageMessage = new ImageMessageBuilder($img_url,$img_url);
               $multiMessage->add($imageMessage);
             }

              $img_index='img_url-1';$img_url=$rec->$img_index;
              if(!empty($img_url)){
              $img_url="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$rec->$img_index;
              $imageMessage = new ImageMessageBuilder($img_url,$img_url);
              $multiMessage->add($imageMessage);
            }

              $img_index='img_url-2';$img_url=$rec->$img_index;
              if(!empty($img_url)){
              $img_url="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$rec->$img_index;
              $imageMessage = new ImageMessageBuilder($img_url,$img_url);
              $multiMessage->add($imageMessage);
            }

              $img_index='img_url-3';$img_url=$rec->$img_index;
              if(!empty($img_url)){
              $img_url="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$rec->$img_index;
              $imageMessage = new ImageMessageBuilder($img_url,$img_url);
              $multiMessage->add($imageMessage);
            }

              $img_index='img_url-4';$img_url=$rec->$img_index;
              if(!empty($img_url)){
              $img_url="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$rec->$img_index;
              $imageMessage = new ImageMessageBuilder($img_url,$img_url);
              $multiMessage->add($imageMessage);
            }// if !empty
            }//end for each
         }// end if isData>0

            $replyData = $multiMessage;
break;

default:
similar_text($explodeText[0],"หวยออกอะไร",$percent_lotto);
similar_text($explodeText[0],"สรุปยอดเงินรุ่น",$percent_finance);
$gallery_keyword = array("หิวนม", "นม", "สาวๆ", "สาวสวย", "สาวน่ารัก");
$fc_keyword = array("fc", "เน็ตไอดอล");
$greeting_keyword = array("hi", "Good morning", "hello", "สวัสดี", "หวัดดี", "หวัดดีลิซ่า", "อรุณสวัสดิ์");

if( $explodeText[0]=='นม' ){
  $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/gallery?apiKey='.MLAB_API_KEY);
  $data = json_decode($json);
    $isData=sizeof($data);
    if($isData >1){
      $img_url=array();
         $count=count($data);
         $index = mt_rand(0,$count-1);
         $textReplyMessage= "มีนมทั้งหมด ".$count." Set นะคะ /n สุ่มได้เซ็ตที่ ".$index.$data[$index]->title;
         $textMessage = new TextMessageBuilder($textReplyMessage);
         $multiMessage->add($textMessage);
         $imgurl0="img_url-0";
         $imgurl=$data[$index]->$imgurl0;
         if(!empty($imgurl)){
         $img_url="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$imgurl;
         $imageMessage = new ImageMessageBuilder($img_url,$img_url);
         $multiMessage->add($imageMessage);
       }
         $imgurl1="img_url-1";
         $imgurl=$data[$index]->$imgurl1;
         if(!empty($imgurl)){
         $img_url="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$imgurl;
         $imageMessage = new ImageMessageBuilder($img_url,$img_url);
         $multiMessage->add($imageMessage);
       }
         $imgurl2="img_url-2";
         $imgurl=$data[$index]->$imgurl2;
         if(!empty($imgurl)){
         $img_url="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$imgurl;
         $imageMessage = new ImageMessageBuilder($img_url,$img_url);
         $multiMessage->add($imageMessage);
       }
         $imgurl3="img_url-3";
         $imgurl=$data[$index]->$imgurl3;
         if(!empty($imgurl)){
         $img_url="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$imgurl;
         $imageMessage = new ImageMessageBuilder($img_url,$img_url);
         $multiMessage->add($imageMessage);
       }
       /*
         $imgurl4="img_url-4";
         $imgurl=$data[$index]->$imgurl4;
         if(!empty($imgurl)){
         $img_url="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$imgurl;
         $imageMessage = new ImageMessageBuilder($img_url,$img_url);
         $multiMessage->add($imageMessage);
       }
*/
    }// end if isData>1
   $replyData = $multiMessage;
}elseif( $explodeText[0]=='วันนี้วันเกิด' && (!empty($explodeText[1])) ){
   $textReplyMessage= "สุขสันต์วันเกิดคุณพี่".$explodeText[1]."\nวันเกิดปีนี้ลิซ่า ขอให้พี่".$explodeText[1]."มีความสุข สุขภาพแข็งแรง สมหวังในทุกสิ่งปราถนา เจริญก้าวหน้าในหน้าที่การงานค่ะ";
   $textMessage = new TextMessageBuilder($textReplyMessage);
   $multiMessage->add($textMessage);
   $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/gallery?apiKey='.MLAB_API_KEY);
   $data = json_decode($json);
   $isData=sizeof($data);
   if($isData >1){
        $count=count($data);
        $index = mt_rand(0,$count-1);
        $imgurl0="img_url-0";
        $imgurl=$data[$index]->$imgurl0;
        if(!empty($imgurl)){
        $img_url="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$imgurl;
        $imageMessage = new ImageMessageBuilder($img_url,$img_url);
        $multiMessage->add($imageMessage);
      }
  }
   $replyData = $multiMessage;
}elseif(in_array($rawText, $gallery_keyword)) {
  $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/gallery?apiKey='.MLAB_API_KEY);
  $img_url=array();
  $img_url=getRandomGallery($json);
  for ($x = 0; $x <= 4; $x++){
    $imageMessage = new ImageMessageBuilder($img_url[$x],$img_url[$x]);
    $multiMessage->add($imageMessage);
  }
  $replyData = $multiMessage;
}elseif(in_array($explodeText[0], $fc_keyword)){ // $text != $gallery_keyword

  $answer_keyword = array("ลิซ่าเป็น FC ป๋าเอี่ยวค่ะ", "ลิซ่าเป็น FC #NP พี่คองคะนองตึกค่ะ", "ลิซ่าเป็น FC เป้หล่อ", "ลิซ่าเป็น FC เป้ผี", "ลิซ่าเป็น FC พี่แดงแมลงวัน", "ลิซ่าเป็น FC พี่หมึกมือยาว", "ลิซ่าเป็น FC พี่เอ้นุ้ย");
  $textReplyMessage= $answer_keyword[array_rand($answer_keyword,1)];
  $textMessage = new TextMessageBuilder($textReplyMessage);
  $multiMessage->add($textMessage);
  $replyData = $multiMessage;
}elseif(in_array($explodeText[0], $greeting_keyword)){ // $text != $gallery_keyword

    $textReplyMessage= welcome($time_of_day);
    $textMessage = new TextMessageBuilder($textReplyMessage);
    $multiMessage->add($textMessage);
    $replyData = $multiMessage;
}elseif($percent_lotto>"80"){ // $text != หวยออกอะไร
  $digits = 3;
  $lotto= rand(pow(10, $digits-1), pow(10, $digits)-1);
        $textReplyMessage= "หวยน่าจะออก ".$lotto." นะคะ ลิซ่าไม่ได้ฝัน แค่เดาเอา \n ถ้าถูกมาเลี้ยงเบียร์ลิซ่าบ้างนะคะ";
        $textMessage = new TextMessageBuilder($textReplyMessage);
        $multiMessage->add($textMessage);
        $replyData = $multiMessage;

}elseif($percent_finance>"80"){ // $text != finance

  $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/finance?apiKey='.MLAB_API_KEY.'&s={"date":-1}&sk=0&l=5');
  $data = json_decode($json);
  $isData=sizeof($data);

  if($isData >0){
$textReplyMessage="รายการความเคลื่อนไหวเงินรุ่น 5 รายการล่าสุดมีดังนี้ค่ะ \n(ณ ขณะนี้ยังไม่ได้เรียงลำดับก่อนหลังนะคะ แค่สุ่มมาแสดงเฉยๆ)\n\n";
    $i=5;
    foreach($data as $rec){
      $add=$rec->add; $add=number_format($add, 2, '.', ',');
      $sub=$rec->sub; $sub=number_format($sub, 2, '.', ',');
      $textReplyMessage=$textReplyMessage.$i." ".$rec->record;
      if(!empty($add)){$textReplyMessage=$textReplyMessage." ".$add." ";}
      if(!empty($sub)){$textReplyMessage=$textReplyMessage." -".$sub."";}
      if(!empty($sum)){$textReplyMessage=$textReplyMessage." ".$sum;}
      $textReplyMessage=$textReplyMessage." บาท\n\n";
        $i--;
    }
    $textMessage = new TextMessageBuilder($textReplyMessage);
    $multiMessage->add($textMessage);
  }
  $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/finance?apiKey='.MLAB_API_KEY.'&q={"type":"summary"}');
  $data = json_decode($json);
  $isData=sizeof($data);
           //$textReplyMessage= $textReplyMessage." isData ".$isData." ค่ะ\n\n";
  if($isData >0){
    foreach($data as $rec){
      $summary=$rec->sum;
      $summary= number_format($summary, 2, '.', ',');
      $textReplyMessage="ยอดเงินรุ่นล่าสุดคงเหลือ ".$summary." บาท\nตรวจสอบข้อมูล ณ วันที่ ".$dateTimeToday." \n\n ข้อมูลของลิซ่าเป็นข้อมูลเบื้องต้น อาจจะไม่อัพเดต \nหากต้องการยืนยันยอด กรุณาติดต่อฝ่ายเหรัญญิกโดยตรงนะค่ะ";
      $textMessage = new TextMessageBuilder($textReplyMessage);
      $multiMessage->add($textMessage);
    }

  }
  $replyData = $multiMessage;

}else{

  $count=0;
/*
  // check in friend databases
  $find_word=$explodeText[0];
  $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY.'&q={"$or":[{"name":{"$regex":"'.$find_word.'"}},{"nickname":{"$regex":"'.$find_word.'"}},{"lastname":{"$regex":"'.$find_word.'"}},{"province":{"$regex":"'.$find_word.'"}},{"detail":{"$regex":"'.$find_word.'"}},{"position":{"$regex":"'.$find_word.'"}}]}');
            $data = json_decode($json);
            $isData=sizeof($data);
           $count = 1;
            if($isData >0){
               foreach($data as $rec){
              $textReplyMessage=$textReplyMessage.$count.' '.$rec->rank.$rec->name.' '.$rec->lastname.' ('.$rec->position.') โทร '.$rec->Tel1." ค่ะ\n\n";
             $count++;
              $img_url=$rec->img_url;
              if(!empty($img_url)&&($count < 5)){
              $img_url="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$rec->img_url;
              $imageMessage = new ImageMessageBuilder($img_url,$img_url);
              $multiMessage->add($imageMessage);
              $count++;
            }// end if ! empty img_url
              }//end for each

          }// end friend isData > 0
*/
 $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/km?apiKey='.MLAB_API_KEY.'&q={"question":"'.$explodeText[0].'"}');

          $data = json_decode($json);
          $isData=sizeof($data);
          //$textReplyMessage= $textReplyMessage." isData ".$isData." ค่ะ\n\n";
          if($isData >0){
             $count=1;
             foreach($data as $rec){

            $textReplyMessage= $textReplyMessage.$rec->answer."\n\n";

            $img_index='img_url-0';$img_url=$rec->$img_index;
            if(!empty($img_url)&&($count < 5)){
            $img_url="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$rec->$img_index;
            $imageMessage = new ImageMessageBuilder($img_url,$img_url);
            $multiMessage->add($imageMessage);
            $count++;
           }

                         $img_index='img_url-1';$img_url=$rec->$img_index;
                         if(!empty($img_url)&&($count < 5)){
                         $img_url="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$rec->$img_index;
                         $imageMessage = new ImageMessageBuilder($img_url,$img_url);
                         $multiMessage->add($imageMessage);
                         $count++;
                       }

                         $img_index='img_url-2';$img_url=$rec->$img_index;
                         if(!empty($img_url)&&($count < 5)){
                         $img_url="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$rec->$img_index;
                         $imageMessage = new ImageMessageBuilder($img_url,$img_url);
                         $multiMessage->add($imageMessage);
                         $count++;
 }

                         $img_index='img_url-3';$img_url=$rec->$img_index;
                         if(!empty($img_url)&&($count < 5)){
                         $img_url="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$rec->$img_index;
                         $imageMessage = new ImageMessageBuilder($img_url,$img_url);
                         $multiMessage->add($imageMessage);
                         $count++;
                       }

                         $img_index='img_url-4';$img_url=$rec->$img_index;
                         if(!empty($img_url)&&($count < 5)){
                         $img_url="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$rec->$img_index;
                         $imageMessage = new ImageMessageBuilder($img_url,$img_url);
                         $multiMessage->add($imageMessage);
                         $count++;
                       }// if !empty

            }//end for each

         }// end if km isData > 0

                $textMessage = new TextMessageBuilder($textReplyMessage);
                $multiMessage->add($textMessage);

                $replyData = $multiMessage;
       }// end if

break;
  }//end switch $explodeText[0]

if(!empty($replyData)){
  // ส่วนส่งกลับข้อมูลให้ LINE
  $response = $bot->replyMessage($replyToken,$replyData);
  /*
  if ($response->isSucceeded()) { echo 'Succeeded!'; return;}
      // Failed ส่งข้อความไม่สำเร็จ
      $statusMessage = $response->getHTTPStatus() . ' ' . $response->getRawBody(); echo $statusMessage;
      $bot->replyText($replyToken, $statusMessage);
      */
}

	}//end if event is textMessage
}// end foreach event
function welcome($H){

   if($H < 12){

     return "อรุณสวัสดิ์ค่ะ";

   }elseif($H > 11 && $H < 13){

     return "สวัสดีตอนเที่ยงค่ะ \nพักรับประทานอาหารกลางวันหรือยังค่ะ";

   }elseif($H > 12 && $H < 18){

     return "สวัสดีตอนบ่ายค่ะ";

   }elseif($H > 17){

     return "สวัสดีตอนเย็นค่ะ";

   }

}

function getRandomGallery($json){

$data = json_decode($json);
  $isData=sizeof($data);
  if($isData >1){
    $img_url=array();
       $count=count($data);
       $index = mt_rand(0,$count-1);
       $imgurl0="img_url-0";
       $imgurl=$data[$index]->$imgurl0;
       if(!empty($imgurl)){
       $img_url[0]="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$imgurl;
     }
       $imgurl1="img_url-1";
       $imgurl=$data[$index]->$imgurl1;
       if(!empty($imgurl)){
       $img_url[1]="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$imgurl;
     }
       $imgurl2="img_url-2";
       $imgurl=$data[$index]->$imgurl2;
       if(!empty($imgurl)){
       $img_url[2]="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$imgurl;
     }
       $imgurl3="img_url-3";
       $imgurl=$data[$index]->$imgurl3;
       if(!empty($imgurl)){
       $img_url[3]="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$imgurl;
     }
       $imgurl4="img_url-4";
       $imgurl=$data[$index]->$imgurl4;
       if(!empty($imgurl)){
       $img_url[4]="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$imgurl;
     }
  }// end if isData>1

return $img_url;
}
class ReplyFlexMessage
{
    /**
     * Create  flex message
     *
     * @return \LINE\LINEBot\MessageBuilder\FlexMessageBuilder
     */
    public static function get($question,$answer)
    {
        return FlexMessageBuilder::builder()
            ->setAltText('Lisa')
            ->setContents(
                BubbleContainerBuilder::builder()
                    ->setHero(self::createHeroBlock())
                    ->setBody(self::createBodyBlock($question,$answer))
                    ->setFooter(self::createFooterBlock())
            );
    }
    private static function createHeroBlock()
    {

        return ImageComponentBuilder::builder()
            ->setUrl('')
            ->setSize(ComponentImageSize::FULL)
            ->setAspectRatio(ComponentImageAspectRatio::R20TO13)
            ->setAspectMode(ComponentImageAspectMode::FIT)
            ->setAction(new UriTemplateActionBuilder(null, 'https://www.thaitimes.online'));
    }
    private static function createBodyBlock($question,$answer)
    {
        $title = TextComponentBuilder::builder()
            ->setText($question)
            ->setWeight(ComponentFontWeight::BOLD)
	          ->setwrap(true)
            ->setSize(ComponentFontSize::SM);

        $textDetail = TextComponentBuilder::builder()
            ->setText($answer)
            ->setSize(ComponentFontSize::LG)
            ->setColor('#000000')
            ->setMargin(ComponentMargin::MD)
	          ->setwrap(true)
            ->setFlex(2);
        $review = BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::VERTICAL)
            ->setMargin(ComponentMargin::LG)
            ->setSpacing(ComponentSpacing::SM)
            ->setContents([$title,$textDetail]);
        return BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::VERTICAL)
            ->setContents([$review]);
    }

    private static function createFooterBlock()
    {

        $websiteButton = ButtonComponentBuilder::builder()
            ->setStyle(ComponentButtonStyle::LINK)
            ->setHeight(ComponentButtonHeight::SM)
            ->setFlex(0)
            ->setAction(new UriTemplateActionBuilder('เพิ่มเติม','https://www.thaitimes.online'));
        $spacer = new SpacerComponentBuilder(ComponentSpaceSize::SM);
        return BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::VERTICAL)
            ->setSpacing(ComponentSpacing::SM)
            ->setFlex(0)
            ->setContents([$websiteButton, $spacer]);
    }
}
