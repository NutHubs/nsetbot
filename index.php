<?php

$strAccessToken = "6qu1XX+9fv8jsUMRV39GsMvl9qiO/RHYpkSH6H2DDEs4xPJ+TL5jSuB6vCpvxEEFXSZOQUs5DmFz8i938BpzeYuWnsIUkRooWQJmVr4Def9WAgyIvrbk+fSfdtlcxt9pc2qNTUF0CsaHVLHYOCIDJAdB04t89/1O/w1cDnyilFU=";
$content = file_get_contents('php://input');
$arrJson = json_decode($content, true);
$strUrl = "https://api.line.me/v2/bot/message/reply";
$arrHeader = array();
$arrHeader[] = "Content-Type: application/json";
$arrHeader[] = "Authorization: Bearer {$strAccessToken}";
$_msg = $arrJson['events'][0]['message']['text'];
$api_key="_ZLyBX6InXGzrE-ki01xKzo-QyXHOwPN";
$url = 'https://api.mlab.com/api/1/databases/nsetbot_db/collections/linebot?apiKey='.$api_key.'';
$json = file_get_contents('https://api.mlab.com/api/1/databases/nsetbot_db/collections/linebot?apiKey='.$api_key.'&q={"question":"'.$_msg.'"}');
$data = json_decode($json);
$isData=sizeof($data);
if (strpos($_msg, 'สอนบอท') !== false) {
  if (strpos($_msg, 'สอนบอท') !== false) {
    $x_tra = str_replace("สอนบอท","", $_msg);
    $pieces = explode("|", $x_tra);
    $_question=str_replace("[","",$pieces[0]);
    $_answer=str_replace("]","",$pieces[1]);
    //Post New Data
    $newData = json_encode(
      array(
        'question' => $_question,
        'answer'=> $_answer
      )
    );
    $opts = array(
      'http' => array(
          'method' => "POST",
          'header' => "Content-type: application/json",
          'content' => $newData
       )
    );
    $context = stream_context_create($opts);
    $returnValue = file_get_contents($url,false,$context);
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = 'ขอบคุณที่สอนบอท';
  }
}else{
  //find loop Json in db
  if($isData >0){
   foreach($data as $rec){
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = $rec->answer;
   }
  }
  else{
    
  if(strtoupper($_msg) == "SERVER TEMP")
  {
    header('Access-Control-Allow-Origin: *');
    $url = "https://api.netpie.io/topic/SmartServerMonitor/ServerRoom1?retain&auth=OLfJOENYvYLmbqG:J0o3U9oywRvgnLtl5lLhscdJ5";
    $response = file_get_contents($url);
    $obj = json_decode($response, true);
    $strTemp = $obj[0]['payload'];
    $arrTemp = explode("|", $strTemp);
    
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = "Server room temp : \n".$arrTemp[0]." °C";
  }
  else if(strtoupper($_msg) == "QC TEMP" || strtoupper($_msg) == "QC TEMPERATURE")
  {
    header('Access-Control-Allow-Origin: *');
    $url = "https://api.netpie.io/topic/NSETEnergySaving/AirCond018/Temperature?retain&auth=ejfAKHEIYXQAJzK:Ni7EbcUpW7KWgsFPQzFEOBWdY";
    $response = file_get_contents($url);
    $obj = json_decode($response, true);
    $strTemp = $obj[0]['payload'];
    //$arrTemp = explode("|", $strTemp);
    
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = "QC Room temp : \n".$strTemp." °C";
  }
  else if(strtoupper($_msg) == "PRODUCTION" || strtoupper($_msg) == "ACTUAL" || strpos($_msg, "product") !== false)
  {
    header('Access-Control-Allow-Origin: *');
    $url = "https://api.netpie.io/topic/SmartCounter/Actual?auth=sq9HZRpoNGgxWIE:pssfGTjYIzmfjnLePlOYkN3oP";
    $response = file_get_contents($url);
    $obj = json_decode($response, true);
    $strActual = $obj[0]['payload'];
	  
    $url1 = "https://api.netpie.io/topic/SmartCounter/Target?auth=sq9HZRpoNGgxWIE:pssfGTjYIzmfjnLePlOYkN3oP";
    $response1 = file_get_contents($url1);
    $obj1 = json_decode($response1, true);
    $strTarget = $obj1[0]['payload'];
    //$arrTemp = explode("|", $strTemp);
    
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = "Target : ".number_format($strTarget)." unit.";
    $arrPostData['messages'][1]['type'] = "text";
    $arrPostData['messages'][1]['text'] = "Actual : ".number_format($strActual)." unit.";
  }
  else if(strtoupper($_msg) == "AIR1")
  {   
    $ch = curl_init("https://api.netpie.io/topic/SmartOfficeNSET/gearname/Air_PAC101_8_CTRL?retain&auth=GWzr8IhAEiqU0bQ:YgXAiVXQakianq4wMZraDMhux");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,"PWR_ON");
    $response = curl_exec($ch);
	  
    $arrJsonX = json_decode($response, true);
	  
    if(strtoupper($arrJsonX['message']) == "SUCCESS")
    {
    	$arrPostData = array();
    	$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    	$arrPostData['messages'][0]['type'] = "text";
    	$arrPostData['messages'][0]['text'] = "OK";
    }
    else
    {
	$arrPostData = array();
    	$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    	$arrPostData['messages'][0]['type'] = "text";
    	$arrPostData['messages'][0]['text'] = "Try again !";
    }
    
  }
    else{
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = 'คุณสามารถสอนบอทให้ฉลาดขึ้นได้ เพียงพิมพ์: สอนบอท[คำถาม|คำตอบ]';
    }
  }
	
}
$channel = curl_init();
curl_setopt($channel, CURLOPT_URL,$strUrl);
curl_setopt($channel, CURLOPT_HEADER, false);
curl_setopt($channel, CURLOPT_POST, true);
curl_setopt($channel, CURLOPT_HTTPHEADER, $arrHeader);
curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode($arrPostData));
curl_setopt($channel, CURLOPT_RETURNTRANSFER,true);
curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($channel);
curl_close ($channel);
?>

