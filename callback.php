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
  $text = strtolower($rawText);
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
				 $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY.'&q={"$or":[{"name":{"$regex":"'.$find_word.'"}},{"lastname":{"$regex":"'.$find_word.'"}},{"province":{"$regex":"'.$find_word.'"}},{"position":{"$regex":"'.$find_word.'"}}]}');
                  $data = json_decode($json);
                  $isData=sizeof($data);
			            $count = 1;
                  if($isData >0){
                     foreach($data as $rec){
                    $textReplyMessage= $textReplyMessage.$count.' '.$rec->rank.$rec->name.' '.$rec->lastname.' ('.$rec->position.') โทร '.$rec->Tel1." ค่ะ\n\n";
				            $count++;
                    }//end for each
		                $textMessage = new TextMessageBuilder($textReplyMessage);
		                $multiMessage->add($textMessage);
                    $replyData = $multiMessage;
	               }else{
			               $textReplyMessage=$textReplyMessage."..ไม่มีข้อมูล.. ";
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
           $replyData = $multiMessage;
          break;
case '$':
if($explodeText[0]!='$'){
$find_word=substr($explodeText[0],1);
 $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/km?apiKey='.MLAB_API_KEY.'&q={"question":{"$regex":"'.$find_word.'"}}');
          $data = json_decode($json);
          $isData=sizeof($data);
          if($isData >0){
             foreach($data as $rec){
            $textReplyMessage= $textReplyMessage.$rec->answer." ค่ะ\n\n";
            }//end for each
            $textMessage = new TextMessageBuilder($textReplyMessage);
            $multiMessage->add($textMessage);
            $replyData = $multiMessage;
         }else{
             $textReplyMessage=$textReplyMessage."..ไม่มีข้อมูล.. ";
             $textMessage = new TextMessageBuilder($textReplyMessage);
             $multiMessage->add($textMessage);
             $replyData = $multiMessage;
            }
    }
break;

default:
 $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/km?apiKey='.MLAB_API_KEY.'&q={"question":{"$regex":"'.$explodeText[0].'"}}');
          $data = json_decode($json);
          $isData=sizeof($data);
          if($isData >0){
             foreach($data as $rec){
            $textReplyMessage= $textReplyMessage.$rec->answer." ค่ะ\n\n";
            }//end for each
            $textMessage = new TextMessageBuilder($textReplyMessage);
            $multiMessage->add($textMessage);
            $replyData = $multiMessage;
         }else{
             $textReplyMessage=$textReplyMessage."..ไม่มีข้อมูล.. ";
             $textMessage = new TextMessageBuilder($textReplyMessage);
             $multiMessage->add($textMessage);
             $replyData = $multiMessage;
            }
break;
  }//end switch $explodeText[0]

	    // ส่วนส่งกลับข้อมูลให้ LINE
      $response = $bot->replyMessage($replyToken,$replyData);
      if ($response->isSucceeded()) { echo 'Succeeded!'; return;}
          // Failed ส่งข้อความไม่สำเร็จ
          $statusMessage = $response->getHTTPStatus() . ' ' . $response->getRawBody(); echo $statusMessage;
          $bot->replyText($replyToken, $statusMessage);
	}//end if event is textMessage
}// end foreach event

// function

function tranlateLang($source, $target, $text_parameter)
{
    $text = str_replace($source,"", $text_parameter);
    $text = str_replace($target,"", $text);
    $trans = new GoogleTranslate();
    $result = $trans->translate($source, $target, $text);
    return $result;
}
class ReplyTranslateMessage
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
            ->setUrl('https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/girls1.jpg')
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

class ReplyPhotoMessage
{
    /**
     * Create  flex message
     *
     * @return \LINE\LINEBot\MessageBuilder\FlexMessageBuilder
     */
    public static function get($answer)
    {
        return FlexMessageBuilder::builder()
            ->setAltText('Lisa')
            ->setContents(
                BubbleContainerBuilder::builder()
                    ->setHero(self::createHeroBlock('https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/girls1.jpg'))
                    ->setBody(self::createBodyBlock($answer))
                    ->setFooter(self::createFooterBlock('https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/girls1.jpg'))
            );
    }
    private static function createHeroBlock($photoUrl)
    {

        return ImageComponentBuilder::builder()
            ->setUrl('https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/girls1.jpg')
            ->setSize(ComponentImageSize::FULL)
            ->setAspectRatio(ComponentImageAspectRatio::R20TO13)
            ->setAspectMode(ComponentImageAspectMode::FIT)
            ->setAction(new UriTemplateActionBuilder(null, 'https://res.cloudinary.com/dly6ftryr/image/upload/v1590735946/girls1.jpg'));
    }
    private static function createBodyBlock($answer)
    {


        $textDetail = TextComponentBuilder::builder()
            ->setText($answer)
            ->setSize(ComponentFontSize::LG)
            ->setColor('#000000')
            ->setMargin(ComponentMargin::MD)
	    ->setwrap(true)
            ->setFlex(2);
        $review = BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::VERTICAL)
            //->setLayout(ComponentLayout::BASELINE)
            ->setMargin(ComponentMargin::LG)
            //->setMargin(ComponentMargin::SM)
            ->setSpacing(ComponentSpacing::SM)
            ->setContents([$textDetail]);

	    /*
        $place = BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::BASELINE)
            ->setSpacing(ComponentSpacing::SM)
            ->setContents([
                TextComponentBuilder::builder()
                    ->setText('ที่อยู่')
                    ->setColor('#aaaaaa')
                    ->setSize(ComponentFontSize::SM)
                    ->setFlex(1),
                TextComponentBuilder::builder()
                    ->setText('Samsen, Bangkok')
                    ->setWrap(true)
                    ->setColor('#666666')
                    ->setSize(ComponentFontSize::SM)
                    ->setFlex(5)
            ]);
        $time = BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::BASELINE)
            ->setSpacing(ComponentSpacing::SM)
            ->setContents([
                TextComponentBuilder::builder()
                    ->setText('Time')
                    ->setColor('#aaaaaa')
                    ->setSize(ComponentFontSize::SM)
                    ->setFlex(1),
                TextComponentBuilder::builder()
                    ->setText('10:00 - 23:00')
                    ->setWrap(true)
                    ->setColor('#666666')
                    ->setSize(ComponentFontSize::SM)
                    ->setFlex(5)
            ]);

        $info = BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::VERTICAL)
            ->setMargin(ComponentMargin::LG)
            ->setSpacing(ComponentSpacing::SM)
            ->setContents([$place, $time]);*/
        return BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::VERTICAL)
            //->setContents([$review, $info]);
            ->setContents([$review]);
    }
    private static function createFooterBlock($photoUrl)
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
