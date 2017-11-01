<?php
  $mid = "777";
  $api_key="_ZLyBX6InXGzrE-ki01xKzo-QyXHOwPN";
  $url = 'https://api.mlab.com/api/1/databases/nsetbot_db/collections/linebot?apiKey='.$api_key.'&q={"mid":"'.$mid.'"}';
  
  $opts = array('http' =>
    array(
      'method' => 'DELETE',
      'header' => 'Content-type: application/json'
    ) 
  );
  
  $context = stream_context_create($opts);
  $returnVal = file_get_contents($url, false, $context);
  echo "Deleted : ".returnVal;
?>
