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
$input = json_decode(file_get_contents('php://input'), true);
$sender = $input['entry'][0]['messaging'][0]['sender']['id'];
$message = $input['entry'][0]['messaging'][0]['message']['text'];
$message_to_reply = '';
/**
 * Some Basic rules to validate incoming messages
 */

$api_key="<mLAP API KEY>";
$url = 'https://api.mlab.com/api/1/databases/duckduck/collections/linebot?apiKey='.$api_key.'';
$json = file_get_contents('https://api.mlab.com/api/1/databases/duckduck/collections/linebot?apiKey='.$api_key.'&q={"question":"'.$message.'"}');
$data = json_decode($json);
$isData=sizeof($data);
if (strpos($message, 'ÊÍ¹à»ç´') !== false) {
  if (strpos($message, 'ÊÍ¹à»ç´') !== false) {
    $x_tra = str_replace("ÊÍ¹à»ç´","", $message);
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
    $message_to_reply = '¢Íº¤Ø³·ÕèÊÍ¹à»ç´';
  }
}else{
  if($isData >0){
   foreach($data as $rec){
     $message_to_reply = $rec->answer;
   }
  }else{
    $message_to_reply = '¡êÒºº ¤Ø³ÊÒÁÒÃ¶ÊÍ¹ãËé©ÅÒ´ä´éà¾ÕÂ§¾ÔÁ¾ì: ÊÍ¹à»ç´[¤Ó¶ÒÁ|¤ÓµÍº]';
  }
}
//API Url
$url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$access_token;
//Initiate cURL.
$ch = curl_init($url);
//The JSON data.
$jsonData = '{
    "recipient":{
        "id":"'.$sender.'"
    },
    "message":{
        "text":"'.$message_to_reply.'"
    }
}';
//Encode the array into JSON.
$jsonDataEncoded = $jsonData;
//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);
//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
//Execute the request
if(!empty($input['entry'][0]['messaging'][0]['message'])){
    $result = curl_exec($ch);
}
?>
