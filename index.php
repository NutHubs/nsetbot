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
  else if(strtoupper($_msg) == "MANPOWER")
  {
	include("lib/nusoap.php");
	$client = new nusoap_client("http://223.27.205.134:12000/Administration/nset_getdata.asmx?wsdl",true); 
	$data = $client->call('chkManpower');
	$mydata = json_decode($data["chkManpowerResult"],true); 
    
    	$arrPostData = array();
    	$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    	$arrPostData['messages'][0]['type'] = "text";
    	$arrPostData['messages'][0]['text'] = "MANPOWER\n-----------------------\n Total : ".number_format($mydata[0]['TTL_MANPOWER'])."\n Indirect : ".number_format($mydata[0]['TTL_INDIRECT'])."\n Direct : ".number_format($mydata[0]['TTL_DIRECT']);
	  
  }
  else if(strtoupper($_msg) == "HOLIDAY")
  {
	include("lib/nusoap.php");
	$client = new nusoap_client("http://223.27.205.134:12000/Administration/nset_getdata.asmx?wsdl",true); 
	$data = $client->call('chkHoliday');
	$mydata = json_decode($data["chkHolidayResult"],true); 
    
    	$arrPostData = array();
    	$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    	$arrPostData['messages'][0]['type'] = "text";
    	$arrPostData['messages'][0]['text'] = "TODAY Holiday : ".$mydata[0]['Total']." person.";
	  
  }
  //who emoployee id
  else if(ereg("^(WHO[[:space:]])([0-9][0-9][0-9][0-9][0-9][0-9])$", strtoupper($_msg)) == true)
  {
	include("lib/nusoap.php");
	$client = new nusoap_client("http://223.27.205.134:12000/Administration/nset_getdata.asmx?wsdl",true); 
	$arrMsg = explode(" ", $_msg);
	$params = array('empID' => (string)$arrMsg[1]);
	$data = $client->call('chkEmployee', $params);
	$mydata = json_decode($data["chkEmployeeResult"],true); 
	//$arrdata = explode("|", $mydata);
    
    	$arrPostData = array();
    	$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    	$arrPostData['messages'][0]['type'] = "text";
    	$arrPostData['messages'][0]['text'] = "Name : ".$mydata[0]['description']."\n Position : ".$mydata[0]['position']."\n Section : ".$mydata[0]['section_name'];
	  
	$arrPostData['messages'][1]['type'] = "image";
    	$arrPostData['messages'][1]['originalContentUrl'] = "https://nsetbot.herokuapp.com/showimage.php?empid=".(string)$arrMsg[1];
	$arrPostData['messages'][1]['previewImageUrl'] = "https://nsetbot.herokuapp.com/showimage.php?empid=".(string)$arrMsg[1];
	  
  }
  else if(strtoupper($_msg) == "OT")
  {
	include("lib/nusoap.php");
	$client = new nusoap_client("http://223.27.205.134:12000/Administration/nset_getdata.asmx?wsdl",true); 
	$data = $client->call('chkOT');
	$mydata = json_decode($data["chkOTResult"],true); 
    	
	$strData = "OT TODAY \n ----------------- \n";
	$strCount = 0;
	  
	foreach ($mydata as $result)
	{
		$strData = $strData.$result["Shop_name"]." : ".$result["Total"]."\n";
		$strCount += (int)$result['Total'];
	}
	  
	$strData = $strData."\n :: Total ::  ".$strCount." person.";
	
    	$arrPostData = array();
    	$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    	$arrPostData['messages'][0]['type'] = "text";
    	$arrPostData['messages'][0]['text'] = $strData;
	  
  }
  else if(strtoupper($_msg) == "MDB1")
  {
    header('Access-Control-Allow-Origin: *');
    $url = "https://api.netpie.io/topic/SmartpowerMeter/electricroom/metermdb1?retain&auth=5tsipuvi6tRjgOX:Y9p0oR3bjlSCiVyNK5PlSNNFY";
    $response = file_get_contents($url);
    $obj = json_decode($response, true);
    $strData = $obj[0]['payload'];
    $arrData = explode("|", $strData);
    
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = "MDB1\n----------\n Frequency : ".((int)$arrData[16] / 100)." Hz. \n PowerFactor : ".$arrData[15]."\n K watt : ".number_format($arrData[14])." Kw \n EC Phase(R) : ".number_format($arrData[1])." Amp";
	  
  }
  else if(strtoupper($_msg) == "MDB2")
  {
    header('Access-Control-Allow-Origin: *');
    $url = "https://api.netpie.io/topic/SmartpowerMeter/electricroom/metermdb2?retain&auth=5tsipuvi6tRjgOX:Y9p0oR3bjlSCiVyNK5PlSNNFY";
    $response = file_get_contents($url);
    $obj = json_decode($response, true);
    $strData = $obj[0]['payload'];
    $arrData = explode("|", $strData);
    
    $varData = "MDB2\n----------\n Frequency : ".((int)$arrData[16] / 100)." Hz. \n PowerFactor : ".$arrData[15]."\n K watt : ".number_format($arrData[14])." Kw \n";
    $varData = $varData."EC Phase(R) : ".number_format($arrData[1])." Amp \nEC Phase(S) : ".number_format($arrData[4])." Amp \nEC Phase(T) : ".number_format($arrData[7])." Amp \n";
    $varData = $varData."Voltage phase R - S : ".number_format(((int)$arrData[3]/10))." V \n"."Voltage phase S - T : ".number_format(((int)$arrData[6]/10))." V \n"."Voltage phase T - R : ".number_format(((int)$arrData[9]/10))." V";
	  
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = $varData;
	  
  }
  else if(strtoupper($_msg) == "MDB3")
  {
    header('Access-Control-Allow-Origin: *');
    $url = "https://api.netpie.io/topic/SmartpowerMeter/electricroom/metermdb3?retain&auth=5tsipuvi6tRjgOX:Y9p0oR3bjlSCiVyNK5PlSNNFY";
    $response = file_get_contents($url);
    $obj = json_decode($response, true);
    $strData = $obj[0]['payload'];
    $arrData = explode("|", $strData);
    
    $varData = "MDB3\n----------\n Frequency : ".((int)$arrData[16] / 100)." Hz. \n PowerFactor : ".$arrData[15]."\n K watt : ".number_format($arrData[14])." Kw \n";
    $varData = $varData."EC Phase(R) : ".number_format($arrData[1])." Amp \nEC Phase(S) : ".number_format($arrData[4])." Amp \nEC Phase(T) : ".number_format($arrData[7])." Amp \n";
    $varData = $varData."Voltage phase R - S : ".number_format(((int)$arrData[3]/10))." V \n"."Voltage phase S - T : ".number_format(((int)$arrData[6]/10))." V \n"."Voltage phase T - R : ".number_format(((int)$arrData[9]/10))." V";
	  
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = $varData;
	  
  }
  else if(strtoupper($_msg) == "MDB4")
  {
    header('Access-Control-Allow-Origin: *');
    $url = "https://api.netpie.io/topic/SmartpowerMeter/electricroom/metermdb4?retain&auth=5tsipuvi6tRjgOX:Y9p0oR3bjlSCiVyNK5PlSNNFY";
    $response = file_get_contents($url);
    $obj = json_decode($response, true);
    $strData = $obj[0]['payload'];
    $arrData = explode("|", $strData);
    
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = "MDB4\n----------\n Frequency : ".((int)$arrData[16] / 100)." Hz. \n PowerFactor : ".$arrData[15]."\n K watt : ".number_format($arrData[14])." Kw \n EC Phase(R) : ".number_format($arrData[1])." Amp \n EC Phase(S) : ".number_format($arrData[4])." Amp";
	  
  }
  else if(strtoupper($_msg) == "WM100" || strpos(strtoupper($_msg), "PROD") !== false && strpos(strtoupper($_msg), "WM100") !== false)
  {
    header('Access-Control-Allow-Origin: *');
    $url = "https://api.netpie.io/topic/SmartMachine/WM100AS/Monitor0?retain&auth=gRYd0nLxFMQiZuP:tKosWuhZZTHNjYdW1Jw3QPTBY";
    $response = file_get_contents($url);
    $obj = json_decode($response, true);
    $strWM100 = $obj[0]['payload'];
    $arrWM100 = explode("|", $strWM100);
    
    date_default_timezone_set("Asia/Bangkok");
    $strH = date('H');
    $strUPH = 0;
	  
    if((int)$strH >= 8 && (int)$strH <= 18)
    {
    	$strUPH =  9 - (18 - (int)$strH);
    }
    else if((int)$strH >= 20 && (int)$strH <= 23)
    {
	$strUPH =  21 - (24 - (int)$strH);
    }
    else if((int)$strH >= 0 && (int)$strH <= 6)
    {
	$strUPH = (int)$strH + 4;
    }
	  
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = "LINE WM100 \n -------------------------- \n OK : ".number_format($arrWM100[1])." Pcs.\n NG : ".number_format($arrWM100[2])." Pcs.\n TOTAL : ".number_format($arrWM100[3])." Pcs.\n"."---------------------------\n UPH : ".number_format((int)$arrWM100[3] / $strUPH);
  }
  else if(strtoupper($_msg) == "WM100 OEE" || strpos(strtoupper($_msg), "WM100") !== false && strpos(strtoupper($_msg), "OEE") !== false)
  {
    header('Access-Control-Allow-Origin: *');
    $url = "https://api.netpie.io/topic/SmartMachine/WM100AS/Monitor0?retain&auth=gRYd0nLxFMQiZuP:tKosWuhZZTHNjYdW1Jw3QPTBY";
    $response = file_get_contents($url);
    $obj = json_decode($response, true);
    $strWM100 = $obj[0]['payload'];
    $arrWM100 = explode("|", $strWM100);
    
    date_default_timezone_set("Asia/Bangkok");
    $strH = date('H');
    $strM = date('i');	  
    $HoureX = 0;
	  
    if((int)$strH >= 8 && (int)$strH <= 18)
    {
    	$HoureX = (int)$strH - 8;
    }
    else if((int)$strH >= 20 && (int)$strH <= 23)
    {
	$HoureX = (int)$strH - 20;
    }
    else if((int)$strH >= 0 && (int)$strH <= 6)
    {
	$HoureX = (int)$strH + 4;
    }
	  
    $strActual = ((int)$arrWM100[1] * 23) / 60;
    $strPlan = ($HoureX *60) + (int)$strM;
	  
    $strPLproduct = ($HoureX * 60) + ((int)$strM / 23);
    $strACproduct = (int)$arrWM100[1];
    
    $varAvability = ($strActual/$strPlan) * 100;
    $varQuality = ((int)$arrWM100[1]/(int)$arrWM100[3])*100;
    $varPerformance = $strACproduct/$strPLproduct* 100;
    $varOEE = ($varAvability * $varQuality * $varPerformance) / 10000;
    
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = " OEE- WM100 \n ------------------ \n Actual : ".(int)$strActual."\n Plan : ".$strPlan."\n PL.product : ".(int)$strPLproduct."\n AC.product : ".$strACproduct."\n Avability : ".round($varAvability,2)." %\n Quality : ".round($varQuality,2)." %\n Performance : ".round($varPerformance,2)." %\n OEE : ".round($varOEE, 2)." %";
	  
  }
  else if(strtoupper($_msg) == "PRODUCTION" || strtoupper($_msg) == "ACTUAL" || strpos(strtoupper($_msg), "PRODUCT") !== false)
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
  else if(strpos(strtoupper($_msg), "หร๊อยหร่อย") !== false)
  {	  
    	$arrPostData = array();
    	$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
	$arrPostData['messages'][0]['type'] = "text";
   	$arrPostData['messages'][0]['text'] = 'อร่อยที่สุดในโลกเลยหล่ะ';
	  
    	$arrPostData['messages'][1]['type'] = "sticker";
    	$arrPostData['messages'][1]['packageId'] = "2";
	$arrPostData['messages'][1]['stickerId'] = "172";
	  
  }
  else
  {
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

