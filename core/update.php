<?php
// Errors not displayed
error_reporting(0);

// Prevent attacks on this file
$telegram_ip_ranges = [['lower' => '149.154.160.0', 'upper' => '149.154.175.255'],['lower' => '91.108.4.0','upper' => '91.108.7.255']];
$ip_dec             = (float) sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
$ok                 = false;

foreach ($telegram_ip_ranges as $telegram_ip_range) {
    if (!$ok) { 
        $lower_dec = (float) sprintf('%u', ip2long($telegram_ip_range['lower']));
        $upper_dec = (float) sprintf('%u', ip2long($telegram_ip_range['upper']));
        if ($ip_dec >= $lower_dec and $ip_dec <= $upper_dec) $ok=true; 
    }
}
if(!$ok) die("go away...");

// Main function
function bot($method,$datas=[]){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,'https://api.telegram.org/bot'.API_KEY.'/'.$method );
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    return json_decode(curl_exec($ch));
}

// Import other files
require_once 'config.php';

// Receive updates from the Telegram server as a webhook
$update     = json_decode(file_get_contents('php://input'));

if(isset($update->message)){
    $message            = $update->message;
    $message_id         = $message->message_id;
    $text               = $message->text;
    $chat_id            = $message->chat->id;
    $from_id            = $message->from->id;
    $tc                 = $message->chat->type;
    $user               = mysqli_fetch_assoc(mysqli_query($connect,"SELECT * FROM `users` WHERE `user_id` = '$from_id' LIMIT 1"));
}elseif(isset($update->callback_query)){
    $callback_query     = $update->callback_query;
    $callback_query_id  = $callback_query->id;
    $data               = $callback_query->data;
    $fromid             = $callback_query->from->id;
    $messageid          = $callback_query->message->message_id;
    $chatid             = $callback_query->message->chat->id;
    $user               = mysqli_fetch_assoc(mysqli_query($connect,"SELECT * FROM `users` WHERE `user_id` = '$fromid' LIMIT 1"));
}

