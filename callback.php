<?php // callback.php
// กรณีต้องการตรวจสอบการแจ้ง error ให้เปิด 3 บรรทัดล่างนี้ให้ทำงาน กรณีไม่ ให้ comment ปิดไป
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__."/vendor/autoload.php";
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
define("MLAB_API_KEY", '6QxfLc4uRn3vWrlgzsWtzTXBW7CYVsQv');
define("LINE_MESSAGING_API_CHANNEL_SECRET", '222aa92cbc8488c8f7660b016e6c990b');
define("LINE_MESSAGING_API_CHANNEL_TOKEN", '14lyWNnWlGRrrXdW5/dW5VKNy2NpTKlg/P1oolT3O3olzt3OR1LDK9G0y7mUBrMXtxPePUIHPWdylLdkROwbOESi4rQE3+oSG3njcFj7yoS5JYgnXlnmwrTmlKC4fs2bjYk8sKUqboRSYUuPnOKXawdB04t89/1O/w1cDnyilFU=');
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
  $rawText = $event->getText();$text = strtolower($rawText); $explodeText=explode(" ",$text);$textReplyMessage="";
	$log_note=$text;
	 $tz_object = new DateTimeZone('Asia/Bangkok');
         $datetime = new DateTime();
         $datetime->setTimezone($tz_object);
         $dateTimeNow = $datetime->format('Y\-m\-d\ H:i:s');
	$replyToken = $event->getReplyToken();	
        $multiMessage =     new MultiMessageBuilder;
	$replyData='No Data';
        $userId=$event->getUserId();
	$res = $bot->getProfile($userId);
         if ($res->isSucceeded()) {
              $profile = $res->getJSONDecodedBody();
              if(!is_null($profile['displayName'])){$displayName = $profile['displayName'];}else{$displayName ='';}
              if(!is_null($profile['statusMessage'])){$statusMessage = $profile['statusMessage'];}else{$statusMessage ='';}
              if(!is_null($profile['pictureUrl'])){$pictureUrl = $profile['pictureUrl'];}else{$pictureUrl ='';}
	      $textReplyMessage= "คุณ".$displayName." คะ\n";
	     
              }

	 // count image in database
	 
	 // end count image in database
	if(!is_null($userId)){
		switch ($explodeText[0]) { 
			case '#':
				
				 $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/phonebook?apiKey='.MLAB_API_KEY.'&q={"$or":[{"name":{"$regex":"'.$explodeText[1].'"}},{"lastname":{"$regex":"'.$explodeText[1].'"}},{"nickname":{"$regex":"'.$explodeText[1].'"}},{"nickname2":{"$regex":"'.$explodeText[1].'"}},{"position":{"$regex":"'.$explodeText[1].'"}}]}');
                                     $data = json_decode($json);
                                     $isData=sizeof($data);
			             $count = 1;
                                     if($isData >0){
		                       $founduser= 1;
                                       foreach($data as $rec){
                                         $textReplyMessage= $textReplyMessage.$count.' '.$rec->rank.$rec->name.' '.$rec->lastname.' ('.$rec->position.' '.$rec->deploy_position.') '.$rec->Email.' โทร '.$rec->Tel1." ค่ะ\n\n";
				         if(isset($rec->Image) and (!$hasImageUrlStatus) and ($count<5)){
		 	                  $imageUrl="https://thaitimes.online/wp-content/uploads/".$rec->Image;
	                                  $imageMessage = new ImageMessageBuilder($imageUrl,$imageUrl);
	                                  $multiMessage->add($imageMessage);
		                            }
			                  $count++;
                                         }//end for each
		                       $textMessage = new TextMessageBuilder($textReplyMessage);
		                       $multiMessage->add($textMessage);
	                              }else{
		                       $founduser= NULL;
			               $textReplyMessage=".... ";
	                               }
				
				$kmText=explode("# ",$text);
				$json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/km?apiKey='.MLAB_API_KEY.'&q={"question":{"$regex":"'.$kmText[0].'"}}');
                                $data = json_decode($json);
                                $isData=sizeof($data);
                                if($isData >0){
                                   foreach($data as $rec){
                                           $textReplyMessage=$kmText[1].".......\n".$rec->answer."\n";
                                           }//end for each
					if(isset($rec->Image)){
		 	                  $picFullSize="https://thaitimes.online/wp-content/uploads/".$rec->Image;
	                                  $imageMessage = new ImageMessageBuilder($picFullSize,$picFullSize);
	                                  $multiMessage->add($imageMessage);
		                            }
			            $foundkm=1;
				    $textMessage = new TextMessageBuilder($textReplyMessage);
		                    $multiMessage->add($textMessage);
                                   }else{//don't found data
					$foundkm=NULL;
					 $textReplyMessage=".... ";
				         }
				
				if(!isset($picFullSize)){	// กรณียังไม่มีรูป จะ Random รูปภาพจากฐานข้อมูลมาแสดง
				$numImg=rand(1,21);
				$json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/img?apiKey='.MLAB_API_KEY.'&q={"no":'.$numImg.'}&max=1');
                                $data = json_decode($json);
                                $isData=sizeof($data);
                                if($isData >0){
                                   foreach($data as $rec){
                                          $picFullSize= "https://thaitimes.online/wp-content/uploads/".$rec->img;
                                           }//end for each
				       $imageMessage = new ImageMessageBuilder($picFullSize,$picFullSize);
	                               $multiMessage->add($imageMessage);
				 }else{//don't found data
					$foundimg=NULL;
					 $textReplyMessage=".... ";
				         }
				}// end isset $picFullSize // มีรูปภาพจาก KM แล้ว
				 
				if($founduser1 or $founduser2 or $foundkm){
					
				       $replyData = $multiMessage;
		                 }else{
				       $textMessage = new TextMessageBuilder($textReplyMessage);
		                       $multiMessage->add($textMessage);
					$replyData = $multiMessage;
				}
                                 break;
				
			   case '#lisa':
				
					
		                $indexCount=1;$answer='';
	                        foreach($explodeText as $rec){
		                       $indexCount++;
		                       if($indexCount>1){
		                           $answer= $answer." ".$explodeText[$indexCount];
		                          }
	                                }
					
                                //Post New Data
                                $newData = json_encode(array('question' => $explodeText[1],'answer'=> $answer) );
                                $opts = array('http' => array( 'method' => "POST",
                                          'header' => "Content-type: application/json",
                                          'content' => $newData
                                           )
                                        );
                                $url = 'https://api.mlab.com/api/1/databases/crma51/collections/km?apiKey='.MLAB_API_KEY.'';
                                $context = stream_context_create($opts);
                                $returnValue = file_get_contents($url,false,$context);
                                       if($returnValue){
		                          $textReplyMessage= $textReplyMessage."\nขอบคุณที่สอนน้อง Lisa ค่ะ";
		                          $textReplyMessage= $textReplyMessage."\nน้อง Lisa จำได้แล้วว่า ".$explodeText[1]." คือ ".$answer;
	                                      }else{ $textReplyMessage= $textReplyMessage."\nน้อง Lisa ไม่เข้าใจค่ะ";
		                                     }
				    $textMessage = new TextMessageBuilder($textReplyMessage);
		                    $multiMessage->add($textMessage);
		                    $replyData = $multiMessage;
				
				
				$numImg=rand(1,21);
				$json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/img?apiKey='.MLAB_API_KEY.'&q={"no":'.$numImg.'}&max=1');
                                $data = json_decode($json);
                                $isData=sizeof($data);
                                if($isData >0){
                                   foreach($data as $rec){
                                          $picFullSize= "https://thaitimes.online/wp-content/uploads/".$rec->img;
                                           }//end for each
				       $imageMessage = new ImageMessageBuilder($picFullSize,$picFullSize);
	                               $multiMessage->add($imageMessage);
					
				}// end no data
				       $replyData = $multiMessage;
                                 break;
			   case '#tran':
			        $text_parameter = str_replace("#tran ","", $text);  
                                if (!is_null($explodeText[1])){ $source =$explodeText[1];}else{$source ='en';}
                                if (!is_null($explodeText[2])){ $target =$explodeText[2];}else{$target ='th';}
                                $result=tranlateLang($source,$target,$text_parameter);
				$flexData = new ReplyTranslateMessage;
                                $replyData = $flexData->get($text_parameter,$result);
				//$log_note=$log_note."\n User select #tran ".$text_parameter.$result;
		                break;
			
			   default: 
		                    $replyData = '';
                                   
				break;
                        }//end switch 
			
			
		
		//-- บันทึกการเข้าใช้งานระบบ ---//
		
              if(!is_null($displayName)){
		      $displayName =$displayName;
	      }elseif(isset($userName)){
		      $displayName =$userName;
		 }else{
		      $displayName = ' ';
	      }
              if(is_null($pictureUrl)){$pictureUrl ='';}
		   $newUserData = json_encode(array('displayName' => $displayName,'userId'=> $userId,'dateTime'=> $dateTimeNow,
						    'log_note'=>$log_note,'pictureUrl'=>$pictureUrl) );
                           $opts = array('http' => array( 'method' => "POST",
                                          'header' => "Content-type: application/json",
                                          'content' => $newUserData
                                           )
                                        );
           
            $url = 'https://api.mlab.com/api/1/databases/crma51/collections/use_log?apiKey='.MLAB_API_KEY.'';
            $context = stream_context_create($opts);
            $returnValue = file_get_contents($url,false,$context);
		
	} // end of !is_null($userId)
	
	
	
            // ส่งกลับข้อมูล
	    // ส่วนส่งกลับข้อมูลให้ LINE
           $response = $bot->replyMessage($replyToken,$replyData);
           if ($response->isSucceeded()) { echo 'Succeeded!'; return;}
              // Failed ส่งข้อความไม่สำเร็จ
             $statusMessage = $response->getHTTPStatus() . ' ' . $response->getRawBody(); echo $statusMessage;
             $bot->replyText($replyToken, $statusMessage);   
	}//end if event is textMessage
}// end foreach event



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
            ->setUrl('https://www.hooq.info/wp-content/uploads/2019/02/Connect-with-precision.jpg')
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
                    ->setHero(self::createHeroBlock('https://thaitimes.online/wp-content/uploads/51724484_1191703040978591_8791088534904635392_n.jpg'))
                    ->setBody(self::createBodyBlock($answer))
                    ->setFooter(self::createFooterBlock('https://thaitimes.online/wp-content/uploads/51724484_1191703040978591_8791088534904635392_n.jpg'))
            );
    }
    private static function createHeroBlock($photoUrl)
    {
	   
        return ImageComponentBuilder::builder()
            ->setUrl('https://thaitimes.online/wp-content/uploads/51724484_1191703040978591_8791088534904635392_n.jpg')
            ->setSize(ComponentImageSize::FULL)
            ->setAspectRatio(ComponentImageAspectRatio::R20TO13)
            ->setAspectMode(ComponentImageAspectMode::FIT)
            ->setAction(new UriTemplateActionBuilder(null, 'https://thaitimes.online/wp-content/uploads/51724484_1191703040978591_8791088534904635392_n.jpg'));
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
