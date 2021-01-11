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
require_once "vendor/restdbclass.php";

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
  //switch ($text[0]) {
    //case '#':
        if($explodeText[0]=='#'){
        $find_word=substr($explodeText[0],1);
        $collectionName = "friend";
        $obj = '{"$or": [{"name":{"$regex":"'.$find_word.'"}},{"nickname":{"$regex":"'.$find_word.'"}},{"lastname":{"$regex":"'.$find_word.'"}},{"province":{"$regex":"'.$find_word.'"}},{"detail":{"$regex":"'.$find_word.'"}},{"telephone":{"$regex":"'.$find_word.'"}}
        ,{"position":{"$regex":"'.$find_word.'"}}]}';
        $sort= 'name';
        $coupon = new RestDB();
        $res = $coupon->selectDocument($collectionName,$obj,$sort);
        $textReplyMessage= "text is ".$find_word."\nfind word is ".$find_word." and Query".$textReplyMessage.$obj;
            $count = 1;
            if($res){
                foreach($res as $rec){
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
                    $noAnser = array("..พักบ้างนะค่ะ.. ","..พี่คิดว่าหนูจะรู้ไหมอ่ะค่ะ.. ","..พี่ธนูว่ายังไงดีค่ะ.. ","..ถามพี่จอห์นเกย์ดีไหมค่ะ.. ","..พี่ถามลิซ่ารึเปล่าค่ะ.. ","..ลิซ่าไม่รู้ค่ะ.. ","ไม่มีใครสอนลิซ่าเรื่องนี้ค่ะ","..ลิซ่าจำไม่ได้ค่ะ..","..วันนี้อากาศดีนะค่ะ..","เอ่อ...ไม่มีข้อมูลค่ะ ลองถามใหม่ดีไหมค่ะ");
                    $count=count($noAnser);
                    $index = mt_rand(0,$count-1);
                    $textReplyMessage=$textReplyMessage.$noAnser[$index];
          			$textMessage = new TextMessageBuilder($textReplyMessage);
                    $multiMessage->add($textMessage);
                    $replyData = $multiMessage;
	                }
      }// end if($explodeText[0]!='#')

  if(!empty($replyData)){
      // ส่วนส่งกลับข้อมูลให้ LINE
      $response = $bot->replyMessage($replyToken,$replyData);
      /*
      if ($response->isSucceeded()) { echo 'Succeeded!'; return;} // Failed ส่งข้อความไม่สำเร็จ
        $statusMessage = $response->getHTTPStatus() . ' ' . $response->getRawBody(); echo $statusMessage;
        $bot->replyText($replyToken, $statusMessage);
      */
  }

 }//end if event is textMessage
}// end foreach event

function welcome($H){
   if($H < 12){                   return "อรุณสวัสดิ์ค่ะ";
   }elseif($H > 11 && $H < 13){   return "สวัสดีตอนเที่ยงค่ะ \nพักรับประทานอาหารกลางวันหรือยังค่ะ";
   }elseif($H > 12 && $H < 18){   return "สวัสดีตอนบ่ายค่ะ";
   }elseif($H > 17){              return "สวัสดีตอนเย็นค่ะ";
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
