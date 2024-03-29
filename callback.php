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
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
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
        $explodeText=explode(",",$text);
        $textReplyMessage="";
        $log_note=$text;


        $replyToken = $event->getReplyToken();

        $multiMessage = new MultiMessageBuilder;

        $replyData='No Data';

        if($text[0]=='$'){
            $find_word=substr($text,1); // ตัด # ตัวแรกออก

            $collectionName = "friend";
            $obj = '{"$or": [{"rank":{"$regex":"'.$find_word.'"}},
            {"name":{"$regex":"'.$find_word.'"}},
            {"lastname":{"$regex":"'.$find_word.'"}},
            {"nickname":{"$regex":"'.$find_word.'"}},
            {"position":{"$regex":"'.$find_word.'"}},
            {"telephone":{"$regex":"'.$find_word.'"}},
            {"organization":{"$regex":"'.$find_word.'"}},
            {"province":{"$regex":"'.$find_word.'"}},
            {"detail":{"$regex":"'.$find_word.'"}}]}';

            $sort= '';

            $friend = new RestDB();
            $res = $friend->selectDocument($collectionName,$obj,$sort);

            $count = 0;
            if(is_array($res)){
                $arr = array();
                foreach($res as $rec){
                    $_id = isset($rec['_id'])?$rec['_id']:"";
                    $rank = isset($rec['rank'])?$rec['rank']:"";
                    $name = isset($rec['name'])?$rec['name']:"";
                    $lastname = isset($rec['lastname'])?$rec['lastname']:"";
                    $nickname = isset($rec['nickname'])?$rec['nickname']:"";
                    $position = isset($rec['position'])?$rec['position']:"";
                    $telephone = isset($rec['telephone'])?$rec['telephone']:"";
                    $organization = isset($rec['organization'])?$rec['organization']:"";
                    $province = isset($rec['province'])?$rec['province']:"";
                    $detail = isset($rec['detail'])?$rec['detail']:"";
                    $image_url = isset($rec['image_url'])?$rec['image_url']:"";
                    if($image_url!=""){
                        $image_url = "https://res.cloudinary.com/crma51/image/upload/v1610664757/$image_url";
                    }else{
                        $image_url = "https://res.cloudinary.com/crma51/image/upload/w_1000,c_fill,ar_1:1,g_auto,r_max,bo_5px_solid_red,b_rgb:262c35/v1610638753/samples/people/boy-snow-hoodie.jpg";
                    }
                    $profile = $rank.$name.' '.$lastname;
                    $contactinfo = $position."\n".$organization."\n".$province."\n";

                    // กำหนด action 4 ปุ่ม 4 ประเภท
                           $actionBuilder = array(
                               new MessageTemplateActionBuilder(
                                   "โทร $telephone",// ข้อความแสดงในปุ่ม
                                   "$telephone" // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                               ),
                               new UriTemplateActionBuilder(
                                   'แจ้งแก้ไขข้อมูล', // ข้อความแสดงในปุ่ม
                                    'https://lisaechobot.herokuapp.com/postnewdata.php?action=showupdateform&updateid='.$_id
                               ),
                               new UriTemplateActionBuilder(
                                   'เพิ่มข้อมูล', // ข้อความแสดงในปุ่ม
                                   'https://lisaechobot.herokuapp.com/postnewdata.php?action=shownewpostform'
                               ),
                           );
                           $newCarousel = new CarouselColumnTemplateBuilder(
                               "$profile",
                               "$contactinfo",
                               $image_url,
                               $actionBuilder
                           );
                              array_push($arr,$newCarousel);

                }//end foreach
                shuffle($arr);
                $num_array = count($arr);
                if($num_array>6){
                    for($x=$num_array;$x>6;$x--){
                        array_pop($arr);
                    }
                }
                $replyData = new TemplateMessageBuilder('แสดงข้อมูลเพื่อน',
                    new CarouselTemplateBuilder(
                        $arr
                    )
                );


                //$textMessage = new TextMessageBuilder($textReplyMessage);
                //$multiMessage->add($textMessage);
                //$replyData = $multiMessage;
	        }else{
                $textReplyMessage="..หนูหาข้อมูล $find_word ไม่พบค่ะ.. ";
          		$textMessage = new TextMessageBuilder($textReplyMessage);
                $multiMessage->add($textMessage);
                $replyData = $multiMessage;
	            }
        }// end if($text[0]!='#')
        elseif($text[0]=='+'){
          $sentence=substr($text,1); // ตัด $ ตัวแรกออก
          $words=explode(",",$sentence);
          $telephone = isset($words[0])?$words[0]:"";
          $rank = isset($words[1])?$words[1]:"";
          $name = isset($words[2])?$words[2]:"";
          $lastname = isset($words[3])?$words[3]:"";
          $nickname = isset($words[4])?$words[4]:"";
          $position = isset($words[5])?$words[5]:"";
          $organization = isset($words[6])?$words[6]:"";
          $province = isset($words[7])?$words[7]:"";
          $detail = isset($words[8])?$words[8]:"";

          $collectionName = "friend";
          $obj = '{"telephone":"'.$telephone.'","rank":"'.$rank.'","name":"'.$name.'","lastname":"'.$lastname.'",
              "nickname":"'.$nickname.'","position":"'.$position.'",
              "organization":"'.$organization.'","province":"'.$province.'","detail":"'.$detail.'"}';

          $km = new RestDB();
          $returnValue = $km->insertDocument($collectionName,$obj);
          if($returnValue){
              $textReplyMessage ="ลิซ่ารับทราบค่ะ \n ลขอบคุณที่แจ้งข้อมูลนะคะ \n ".$rank." ".$name." ".$lastname." ".$position." ".$telephone." ".$organization." ".$province;
          }else{
              $textReplyMessage = "Lisa งงค่ะ";
          }

          $textMessage = new TextMessageBuilder($textReplyMessage);
          $multiMessage->add($textMessage);
          $replyData = $multiMessage;
      }// end elseif $
      elseif($text[0]=='?'){

          if(!empty($text)){
              $text = substr($text,1);
              $collectionName = "kmdata";
              $obj = '{"question":{"$regex":"'.$text.'"}}';
              $sort= '';
              $news = new RestDB();
              $res = $news->selectDocument($collectionName,$obj,$sort);
              if($res){
                  $arr = array();
                  foreach($res as $rec){
                      $question = isset($rec['question']) ? $rec['question'] : 'question';
                      $answer = isset($rec['answer']) ? $rec['answer'] : 'answer';
                      $image_url = isset($rec['image_url']) ? $rec['image_url'] : 'news/jazz.jpg';
                      $imageUrl = "https://res.cloudinary.com/crma51/image/upload/v1610664757/$image_url";
                      // กำหนด action 4 ปุ่ม 4 ประเภท
                      $actionBuilder = array( );

                             $newCarousel = new CarouselColumnTemplateBuilder(
                                 "$question",
                                 "$answer",
                                 $imageUrl,
                                 $actionBuilder
                             );

                             if($count<7){
                                array_push($arr,$newCarousel);
                             }
                  }//end foreach

                  $replyData = new TemplateMessageBuilder('แสดงข้อมูลถามตอบ',
                      new CarouselTemplateBuilder(
                          $arr
                      )
                  );

              }// end no result

          }// if not empty text
          else{ // if just ?
             $textReplyMessage = "กด $ ตามด้วยชื่อบุคคล ตำแหน่ง สังกัด จังหวัด เพื่อค้นหาข้อมูลบุคคล<br>";
             $textReplyMessage = $textReplyMessage."กด # ตามด้วยคำที่ต้องการค้นหา<br>";
             $textMessage = new TextMessageBuilder($textReplyMessage);
             $multiMessage->add($textMessage);
             $replyData = $multiMessage;
          }
        //$replyData = $multiMessage;
    }// end elseif =
        elseif($text[0]=='!'){
          $sentence=substr($text,1); // ตัด $ ตัวแรกออก
          $words=explode(",",$sentence);
          $question = $words[0];

          $length = strlen($question);
          $answer = substr_replace($sentence, '', 0,$length);

          $collectionName = "kmdata";
          $obj = '{"question":"'.$question.'","answer":"'.$answer.'"}';

          $km = new RestDB();
          $returnValue = $km->insertDocument($collectionName,$obj);
          if($returnValue){
              $textReplyMessage ="ขอบคุณที่สอนลิซ่าค่ะ \n ลิซ่าจำได้แล้ว ถ้าถามว่า ".$question." ให้ตอบ ".$answer;
          }else{
              $textReplyMessage = "Lisa งงค่ะ";
          }

          $textMessage = new TextMessageBuilder($textReplyMessage);
          $multiMessage->add($textMessage);
          $replyData = $multiMessage;
      }// end elseif !
      elseif($text[0]=='#'){ // first text is not #
              $text = substr($text,1);
        if(!empty($text)){
              $collectionName = "post";
              $obj = '{"$or": [{"title":{"$regex":"'.$text.'"}},
              {"tag":{"$regex":"'.$text.'"}},
              {"image_url":{"$regex":"'.$text.'"}}]}';

              $sort= '';
              $coupon = new RestDB();
              $res = $coupon->selectDocument($collectionName,$obj,$sort);
              if($res){
                  $arr = array();
                  foreach($res as $rec){
                      $title = isset($rec['title']) ? $rec['title'] : 'Title';
                      $tag = isset($rec['tag']) ? $rec['tag'] : 'tag';
                      $image_url = isset($rec['image_url']) ? $rec['image_url'] : 'post/1.jpg';
                      $imageUrl = "https://res.cloudinary.com/crma51/image/upload/v1610664757/$image_url";
                      $description = "$ สอบถามข้อมูลบุคคล # ค้นหารูปภาพ ? สอบถามวิธีใช้งานและเรื่องอื่น ๆ";
                      // กำหนด action 4 ปุ่ม 4 ประเภท
                      $actionBuilder = array(

                                  new UriTemplateActionBuilder(
                                      'ดูข้อมูล', // ข้อความแสดงในปุ่ม
                                       $imageUrl
                                  ),
                                 new UriTemplateActionBuilder(
                                     'เพิ่มข้อมูล', // ข้อความแสดงในปุ่ม
                                     'https://lisaechobot.herokuapp.com/postnewdata.php?action=shownewpostform'
                                 )
                             );

                             $newCarousel = new CarouselColumnTemplateBuilder(
                                 "$title",
                                 $description,
                                 $imageUrl,
                                 $actionBuilder
                             );
                            array_push($arr,$newCarousel);

                  }//end foreach
                  shuffle($arr);
                  $count = count($arr);
                  if($count>5){
                      for($x=$count;$x>5;$x--){
                          array_pop($arr);
                      }
                   }
                  $replyData = new TemplateMessageBuilder('แสดงข้อมูลรูปภาพ',
                      new CarouselTemplateBuilder(
                          $arr
                      )
                  );

                 // $textReplyMessage=$textReplyMessage.$title.$tag.$imageUrl;
                 // $textMessage = new TextMessageBuilder($textReplyMessage);
                 // $multiMessage->add($textMessage);
                 // $replyData = $multiMessage;
              }// end if result from database


          }// end id not empty text

      }//end if else #
      elseif($explodeText[0]=='news'){ // first text is not #
          $words=explode(",",$text);
          $title = $words[1];
          $detail = $words[2];

          $collectionName = "news";
          $obj = '{"title":"'.$title.'","detail":"'.$detail.'","date":"'.$dateTimeToday.'"}';

          $km = new RestDB();
          $returnValue = $km->insertDocument($collectionName,$obj);
          if($returnValue){
              $textReplyMessage ="ส่งข่าวเรียบร้อย ";
          }else{
              $textReplyMessage = "ไม่สามารถส่งข่าวได้";
          }

          $textMessage = new TextMessageBuilder($textReplyMessage);
          $multiMessage->add($textMessage);
          $replyData = $multiMessage;
      }//end if text = news
      else{ // first text is not #
          echo "ไม่ได้เลือกหัวข้อใดๆ";
        }//end if else

        if(!empty($replyData)){
            // ส่วนส่งกลับข้อมูลให้ LINE
            $response = $bot->replyMessage($replyToken,$replyData);
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
function setFlexTemplate(){
$flexMessage='' ;
return $flexMessage;
}
function getRandomGallery($json){
  $data = json_decode($json);
  $isData=sizeof($data);
  if($isData >1){
    $image_url=array();
       $count=count($data);
       $index = mt_rand(0,$count-1);
       $imgurl0="image_url-0";
       $imgurl=$data[$index]->$imgurl0;
       if(!empty($imgurl)){
       $image_url[0]="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$imgurl;
     }
       $imgurl1="image_url-1";
       $imgurl=$data[$index]->$imgurl1;
       if(!empty($imgurl)){
       $image_url[1]="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$imgurl;
     }
       $imgurl2="image_url-2";
       $imgurl=$data[$index]->$imgurl2;
       if(!empty($imgurl)){
       $image_url[2]="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$imgurl;
     }
       $imgurl3="image_url-3";
       $imgurl=$data[$index]->$imgurl3;
       if(!empty($imgurl)){
       $image_url[3]="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$imgurl;
     }
       $imgurl4="image_url-4";
       $imgurl=$data[$index]->$imgurl4;
       if(!empty($imgurl)){
       $image_url[4]="https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/".$imgurl;
     }
  }// end if isData>1
  return $image_url;
}
