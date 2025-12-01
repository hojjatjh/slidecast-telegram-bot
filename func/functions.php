<?php
function make_slug($text) {
    // Trim
    $text = trim($text);

    // lowercase UTF-8
    $text = mb_strtolower($text, 'UTF-8');

    // Convert space to _ 
    $text = preg_replace('/[\s_]+/u', '-', $text);

    // Remove unknown character
    $text = preg_replace('/[^a-z0-9\-ء-يآأإؤئًٌٍَُِّۀہچژکگپدیصضطظقغءشسیبلاتنمکهوفرئیة]+/u', '', $text);

    // Delete - consecutive
    $text = preg_replace('/-+/', '-', $text);

    // Delete - beginning and end
    $text = trim($text, '-');
    
    return $text;
}
function isBotAdmin($token, $chat_id) {
    $botInfo = json_decode(file_get_contents("https://api.telegram.org/bot$token/getMe"), true);

    if (!$botInfo["ok"]) {
        return false; // bot info failed
    }
    $bot_id = $botInfo["result"]["id"];
    $url    = "https://api.telegram.org/bot$token/getChatMember?chat_id={$chat_id}&user_id={$bot_id}";
    $res    = json_decode(file_get_contents($url), true);
    if (!$res["ok"]) {
        return false; // unable to check admin status
    }
    $status = $res["result"]["status"];
    // status admin or owner
    return ($status === "administrator" || $status === "creator");
}