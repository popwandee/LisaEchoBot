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
$log_note='';
$eventObj = $events[0];
$eventType = $eventObj->getType();
$replyToken = $eventObj->getReplyToken();
switch($eventType){
    case 'message': $eventMessage = true; break;
    case 'postback': $eventPostback = true; break;
    case 'join': $eventJoin = true; break;
    case 'leave': $eventLeave = true; break;
    case 'follow': $eventFollow = true; break;
    case 'unfollow': $eventUnfollow = true; break;
    case 'beacon': $eventBeacon = true; break;
    case 'accountLink': $eventAccountLink = true; break;
    case 'memberJoined': $eventMemberJoined = true; break;
    case 'memberLeft': $eventMemberLeft = true; break;
}
// สร้างตัวแปรเก็บค่า userId กรณีเป็น Event ที่เกิดขึ้นใน USER
if($eventObj->isUserEvent()){
    $userId = $eventObj->getUserId();
    $sourceType = "USER";
}
// สร้างตัวแปรเก็บค่า groupId กรณีเป็น Event ที่เกิดขึ้นใน GROUP
if($eventObj->isGroupEvent()){
    $groupId = $eventObj->getGroupId();
    $userId = $eventObj->getUserId();
    $sourceType = "GROUP";
}
// สร้างตัวแปรเก็บค่า roomId กรณีเป็น Event ที่เกิดขึ้นใน ROOM
if($eventObj->isRoomEvent()){
    $roomId = $eventObj->getRoomId();
    $userId = $eventObj->getUserId();
    $sourceType = "ROOM";
}
// เก็บค่า sourceId ปกติจะเป็นค่าเดียวกันกับ userId หรือ roomId หรือ groupId ขึ้นกับว่าเป็น event แบบใด
$sourceId = $eventObj->getEventSourceId();
// ดึงค่า replyToken มาไว้ใช้งาน ทุกๆ Event ที่ไม่ใช่ Leave และ Unfollow Event และ  MemberLeft
// replyToken ไว้สำหรับส่งข้อความตอบกลับ
if(is_null($eventLeave) && is_null($eventUnfollow) && is_null($eventMemberLeft)){
    $replyToken = $eventObj->getReplyToken();
}
 // ส่วนของการทำงาน
if(!is_null($events)){

    // ถ้า bot ถูก invite เพื่อเข้า Join Event ให้ bot ส่งข้อความใน GROUP ว่าเข้าร่วม GROUP แล้ว
    if(!is_null($eventJoin)){
        $textReplyMessage = "ขอเข้าร่วมด้วยน่ะ $sourceType ID:: ".$sourceId;
        $replyData = new TextMessageBuilder($textReplyMessage);
    }

    // ถ้า bot ออกจาก สนทนา จะไม่สามารถส่งข้อความกลับได้ เนื่องจากไม่มี replyToken
    if(!is_null($eventLeave)){

    }

    // ถ้า bot ถูกเพื่มเป้นเพื่อน หรือถูกติดตาม หรือ ยกเลิกการ บล็อก
    if(!is_null($eventFollow)){
        $textReplyMessage = "ขอบคุณที่เป็นเพื่อน และติดตามเรา";
        $replyData = new TextMessageBuilder($textReplyMessage);
    }

    // ถ้า bot ถูกบล็อก หรือเลิกติดตาม จะไม่สามารถส่งข้อความกลับได้ เนื่องจากไม่มี replyToken
    if(!is_null($eventUnfollow)){

    }
// ถ้ามีสมาชิกคนอื่น เข้ามาร่วมใน room หรือ group
    // room คือ สมมติเราคุยกับ คนหนึ่งอยู่ แล้วเชิญคนอื่นๆ เข้ามาสนทนาด้วย จะกลายเป็นห้องใหม่
    // group คือ กลุ่มที่เราสร้างไว้ มีชื่อกลุ่ม แล้วเราเชิญคนอื่นเข้ามาในกลุ่ม เพิ่มร่วมสนทนาด้วย
    if(!is_null($eventMemberJoined)){
            $arr_joinedMember = $eventObj->getEventBody();
            $joinedMember = $arr_joinedMember['joined']['members'][0];
            if(!is_null($groupId) || !is_null($roomId)){
                if($eventObj->isGroupEvent()){
                    foreach($joinedMember as $k_user=>$v_user){
                        if($k_user=="userId"){
                            $joined_userId = $v_user;
                        }
                    }
                    $response = $bot->getGroupMemberProfile($groupId, $joined_userId);
                }
                if($eventObj->isRoomEvent()){
                    foreach($joinedMember as $k_user=>$v_user){
                        if($k_user=="userId"){
                            $joined_userId = $v_user;
                        }
                    }
                    $response = $bot->getRoomMemberProfile($roomId, $joined_userId);
                }
            }else{
                $response = $bot->getProfile($userId);
            }
            if ($response->isSucceeded()) {
                $userData = $response->getJSONDecodedBody(); // return array
                $userId= $userData['userId'];
                $displayName= $userData['displayName'];
                $pictureUrl= $userData['pictureUrl'];
                $statusMessage= $userData['statusMessage'];
                $textReplyMessage = 'สวัสดีครับ คุณ '.$displayName;
            }else{
                $textReplyMessage = 'สวัสดีครับ ยินดีต้อนรับ';
            }
//        $textReplyMessage = "ยินดีต้อนรับกลับมาอีกครั้ง ".json_encode($joinedMember);
        $replyData = new TextMessageBuilder($textReplyMessage);
    }
	 // ถ้ามีสมาชิกคนอื่น ออกจากก room หรือ group จะไม่สามารถส่งข้อความกลับได้ เนื่องจากไม่มี replyToken
    if(!is_null($eventMemberLeft)){

    }

    // ถ้ามีกาาเชื่อมกับบัญชี LINE กับระบบสมาชิกของเว็บไซต์เรา
    if(!is_null($eventAccountLink)){
        // หลักๆ ส่วนนี้ใช้สำรหบัเพิ่มความภัยในการเชื่อมบัญตี LINE กับระบบสมาชิกของเว็บไซต์เรา
        $textReplyMessage = "AccountLink ทำงาน ".$replyToken." Nonce: ".$eventObj->getNonce();
        $replyData = new TextMessageBuilder($textReplyMessage);
    }

    // ถ้าเป็น Postback Event
    if(!is_null($eventPostback)){
        $dataPostback = NULL;
        $paramPostback = NULL;
        // แปลงข้อมูลจาก Postback Data เป็น array
        parse_str($eventObj->getPostbackData(),$dataPostback);
        // ดึงค่า params กรณีมีค่า params
        $paramPostback = $eventObj->getPostbackParams();
        // ทดสอบแสดงข้อความที่เกิดจาก Postaback Event

        $textReplyMessage = "ข้อความจาก Postback Event Data = ";
        $textReplyMessage.= json_encode($dataPostback);
        $textReplyMessage.= json_encode($paramPostback);

	    $textReplyMessage=$dataPostback['Result'];
        $replyData = new TextMessageBuilder($textReplyMessage);
    }
    // ถ้าเป้น Message Event
    if(!is_null($eventMessage)){

        //$textReplyMessage = $textReplyMessage."กรณี ข้อความ ";
        // สร้างตัวแปรเก็ยค่าประเภทของ Message จากทั้งหมด 7 ประเภท
        $typeMessage = $eventObj->getMessageType();
        //  text | image | sticker | location | audio | video | file
        // เก็บค่า id ของข้อความ
        $idMessage = $eventObj->getMessageId();
        // ถ้าเป็นข้อความ
        if($typeMessage=='text'){
            $userMessage = $eventObj->getText(); // เก็บค่าข้อความที่ผู้ใช้พิมพ์
              //$textReplyMessage = $textReplyMessage."ข้อความ Text.";
        }
        // ถ้าเป็น image
        if($typeMessage=='image'){
          //$textReplyMessage = $textReplyMessage."ข้อความ image.";

        }
        // ถ้าเป็น audio
        if($typeMessage=='audio'){
          //$textReplyMessage = $textReplyMessage."ข้อความ audio.";

        }
        // ถ้าเป็น video
        if($typeMessage=='video'){
          //$textReplyMessage = $textReplyMessage."ข้อความ video.";

        }
        // ถ้าเป็น file
        if($typeMessage=='file'){
            $FileName = $eventObj->getFileName();
            $FileSize = $eventObj->getFileSize();
              //$textReplyMessage = $textReplyMessage."ข้อความ file.";
        }
	    /*
        // ถ้าเป็น image หรือ audio หรือ video หรือ file และต้องการบันทึกไฟล์
        if(preg_match('/image|audio|video|file/',$typeMessage)){
            $responseMedia = $bot->getMessageContent($idMessage);
            if ($responseMedia->isSucceeded()) {
                // คำสั่ง getRawBody() ในกรณีนี้ จะได้ข้อมูลส่งกลับมาเป็น binary
                // เราสามารถเอาข้อมูลไปบันทึกเป็นไฟล์ได้
                $dataBinary = $responseMedia->getRawBody(); // return binary
                // ดึงข้อมูลประเภทของไฟล์ จาก header
                $fileType = $responseMedia->getHeader('Content-Type');
                switch ($fileType){
                    case (preg_match('/^application/',$fileType) ? true : false):
//                      $fileNameSave = $FileName; // ถ้าต้องการบันทึกเป็นชื่อไฟล์เดิม
                        $arr_ext = explode(".",$FileName);
                        $ext = array_pop($arr_ext);
                        $fileNameSave = time().".".$ext;
                        break;
                    case (preg_match('/^image/',$fileType) ? true : false):
                        list($typeFile,$ext) = explode("/",$fileType);
                        $ext = ($ext=='jpeg' || $ext=='jpg')?"jpg":$ext;
                        $fileNameSave = time().".".$ext;
                        break;
                    case (preg_match('/^audio/',$fileType) ? true : false):
                        list($typeFile,$ext) = explode("/",$fileType);
                        $fileNameSave = time().".".$ext;
                        break;
                    case (preg_match('/^video/',$fileType) ? true : false):
                        list($typeFile,$ext) = explode("/",$fileType);
                        $fileNameSave = time().".".$ext;
                        break;
                }
                $botDataFolder = 'botdata/'; // โฟลเดอร์หลักที่จะบันทึกไฟล์
                $botDataUserFolder = $botDataFolder.$userId; // มีโฟลเดอร์ด้านในเป็น userId อีกขั้น
                if(!file_exists($botDataUserFolder)) { // ตรวจสอบถ้ายังไม่มีให้สร้างโฟลเดอร์ userId
                    mkdir($botDataUserFolder, 0777, true);
                }
                // กำหนด path ของไฟล์ที่จะบันทึก
                $fileFullSavePath = $botDataUserFolder.'/'.$fileNameSave;
//              file_put_contents($fileFullSavePath,$dataBinary); // เอา comment ออก ถ้าต้องการทำการบันทึกไฟล์
                $textReplyMessage = "บันทึกไฟล์เรียบร้อยแล้ว $fileNameSave";
                $replyData = new TextMessageBuilder($textReplyMessage);
//              $failMessage = json_encode($fileType);
//              $failMessage = json_encode($responseMedia->getHeaders());
//              $replyData = new TextMessageBuilder($failMessage);
            }else{
                $failMessage = json_encode($idMessage.' '.$responseMedia->getHTTPStatus() . ' ' . $responseMedia->getRawBody());
                $replyData = new TextMessageBuilder($failMessage);
            }
        }
	*/
        // ถ้าเป็น sticker
        if($typeMessage=='sticker'){
            $packageId = $eventObj->getPackageId();
            $stickerId = $eventObj->getStickerId();
              //$textReplyMessage = $textReplyMessage."ข้อความ sticker.";
        }
        // ถ้าเป็น location
        if($typeMessage=='location'){
            $locationTitle = $eventObj->getTitle();
            $locationAddress = $eventObj->getAddress();
            $locationLatitude = $eventObj->getLatitude();
            $locationLongitude = $eventObj->getLongitude();
              //$textReplyMessage = $textReplyMessage."ข้อความ Location.";
        }


        switch ($typeMessage){ // กำหนดเงื่อนไขการทำงานจาก ประเภทของ message
            case 'text':  // ถ้าเป็นข้อความ

                  $replyToken = $eventObj->getReplyToken();
              //$textReplyMessage = $textReplyMessage."\n case  Text.";
	            $tz_object = new DateTimeZone('Asia/Bangkok');
              $datetime = new DateTime();
              $datetime->setTimezone($tz_object);
              $dateTimeNow = $datetime->format('Y\-m\-d\ H:i:s');
              $multiMessage =     new MultiMessageBuilder;
              $userMessage = strtolower($userMessage); // แปลงเป็นตัวเล็ก สำหรับทดสอบ
	            $explodeText=explode(" ",$userMessage);
	            $log_note=$userMessage;

              $textReplyMessage=". reply Token is.. ".$replyToken;
              $textMessage = new TextMessageBuilder($textReplyMessage);
              $multiMessage->add($textMessage);
              switch ($userMessage[0]) {

		case '#':
        //$textReplyMessage = $textReplyMessage.'Case # ask people. ';
        $find_word=substr($explodeText[0], 1);
        //$textReplyMessage =$textReplyMessage.'Fine word '.$find_word.' ask people.';
				 $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY.'&q={"$or":[{"name":{"$regex":"'.$find_word.'"}},{"lastname":{"$regex":"'.$find_word.'"}},{"province":{"$regex":"'.$find_word.'"}},{"position":{"$regex":"'.$find_word.'"}}]}');
                  $data = json_decode($json);
                  $isData=sizeof($data);
			            $count = 1;
                  if($isData >0){
                         //$textReplyMessage = $textReplyMessage."Found people. \n";
                     foreach($data as $rec){
                        $textReplyMessage= $textReplyMessage.$count.' '.$rec->rank.$rec->name.' '.$rec->lastname.' ('.$rec->position.') โทร '.$rec->Tel1." ค่ะ\n\n";
				         /*if(isset($rec->Image) and (!$hasImageUrlStatus) and ($count<5)){
		 	                  $imageUrl="https://thaitimes.online/wp-content/uploads/".$rec->Image;
	                                  $imageMessage = new ImageMessageBuilder($imageUrl,$imageUrl);
	                                  $multiMessage->add($imageMessage);
		                            }*/
			                  $count++;
                    }//end for each
		                $textMessage = new TextMessageBuilder($textReplyMessage);
		                $multiMessage->add($textMessage);
                    $replyData = $multiMessage;
	               }else{
		                 $founduser= NULL;
			               $textReplyMessage=$textReplyMessage."..ไม่มีข้อมูล.. ";
          				   $textMessage = new TextMessageBuilder($textReplyMessage);
                     $multiMessage->add($textMessage);
                     $replyData = $multiMessage;
	                               }
                  break;
     case '?':
          //$textReplyMessage = $textReplyMessage.'Case # ask people. ';
          $find_word=substr($explodeText[0], 1);
          //$textReplyMessage =$textReplyMessage.'Fine word '.$find_word.' ask people.';
          $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/km?apiKey='.MLAB_API_KEY.'&q={"question":{"$regex":"'.$find_word.'"}}');
          $data = json_decode($json);
          $isData=sizeof($data);
          $count = 1;
          if($isData >0){
          foreach($data as $rec){
            $textReplyMessage = $rec->answer."\n\n";
              				         /*if(isset($rec->Image) and (!$hasImageUrlStatus) and ($count<5)){
              		 	                  $imageUrl="https://thaitimes.online/wp-content/uploads/".$rec->Image;
              	                                  $imageMessage = new ImageMessageBuilder($imageUrl,$imageUrl);
              	                                  $multiMessage->add($imageMessage);
              		                            }*/
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
          break;
                  case '@':
                      $indexCount=1;$answer='';
                      foreach($explodeText as $rec){
                      $indexCount++;
                        if($indexCount>1){//น่าจะมีคำถามและคำตอบมาด้วย
                          $answer= $answer." ".$explodeText[$indexCount];
                        }
                      }//end foreach $explodeText นับจำนวนคำ เพื่อตรวจสอบว่ามีคำถามและคำตอบมาด้วย
                      if(($indexCount>1) && (!empty($explodeText[1]))){
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
                            $textReplyMessage= $textReplyMessage."\nน้อง Lisa จำได้แล้วว่า ".$explodeText[1]." คือ ".$answer;
                            }else{ $textReplyMessage= $textReplyMessage."\nน้อง Lisa ไม่เข้าใจค่ะ";
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
                    default:
                        //$textReplyMessage = " คุณไม่ได้พิมพ์ ค่า ตามที่กำหนด";
                       // $replyData = new TextMessageBuilder($textReplyMessage);
                        break;
                }// end switch ($userMessage)

                break;
            default:
                if(!is_null($replyData)){

                }else{
                    // กรณีทดสอบเงื่อนไขอื่นๆ ผู้ใช้ไม่ได้ส่งเป็นข้อความ
                   // $textReplyMessage = 'สวัสดีครับ คุณ '.$typeMessage;
                    //$replyData = new TextMessageBuilder($textReplyMessage);
                }
                break;
        }// end switch (typemessage)

    }
}
/*
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

            */
$response = $bot->replyMessage($replyToken,$replyData);
if ($response->isSucceeded()) {
    echo 'Succeeded!';
    return;
}
// Failed
echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

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
