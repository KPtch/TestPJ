<?php
  $access_token = "EAACdZCFrDYhoBAKYNs3Tq78I032Fxfijsd1apR7sVt1XaAWXYeLJ3OulU5L60M15hWnUT0RldRXivEp7hgGNzb47MbcOzeR2z6JGYVoN5WCVpn0e2ZCZB1epOBNSgc8dY4tZBH6CJZAz7ZCXnV5nXTrsM2GFH5QzvJczpTO9Uu1YZBADo2Lbn6HIqO6HEVByiEZD";


  $verify_token = "KUNSRI3";
  $hub_verify_token = null;
  if(isset($_REQUEST['hub_challenge'])) {
    $challenge = $_REQUEST['hub_challenge'];
    $hub_verify_token = $_REQUEST['hub_verify_token'];
  }
  if ($hub_verify_token === $verify_token) {
    echo $challenge;
  }

  //API Url
  $url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$access_token;
  

  $input    = json_decode(file_get_contents('php://input'), true);

  $sender   = $input['entry'][0]['messaging'][0]['sender']['id'];
  $message  = $input['entry'][0]['messaging'][0]['message']['text'];

  $ch = curl_init($url);
  $jsonData = '{
    "recipient":{
        "id":"'.$sender.'"
    },
    "message":{
        "text":"'.'test message to reply u message : '.$message.'"
    }
  }';

  $jsonDataEncoded = $jsonData;
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
  if(!empty($input['entry'][0]['messaging'][0]['message'])){
    $result = curl_exec($ch);
    
  }
  curl_close($ch);
?>
