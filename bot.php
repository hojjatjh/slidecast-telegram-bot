<?php
error_reporting(0);

// Import other files
require_once 'core/update.php';
require_once 'func/lang.php';

// Admin language selection
if (in_array($from_id, $admin_user_id)){
    if($user['user_id'] != true or $user['lang'] == 'not_set'){
        file_put_contents('d.txt', "user = {$user['user_id']} - lang: {$user['lang']}");
        if ($user['user_id'] != true){
            $connect->query("INSERT INTO `users` (`user_id` , `lang`) VALUES ('$from_id' , 'not_set')");
        }
        bot('sendmessage',[
            'chat_id'=>$from_id,
            'text'=>"🇺🇸 Please select your language to continue\n🇸🇦 يُرجى اختيار لغتك للمتابعة\n🇮🇷 لطفا زبان خودرا انتخاب کنید\n",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    [['text'=>"🇮🇷",'callback_data'=>"setlang|1"]],
                    [['text'=>"🇺🇸",'callback_data'=>"setlang|2"],['text'=>"🇸🇦",'callback_data'=>"setlang|3"]],
                ]
            ])
        ]);
        exit;
    }
}
if ($text == '/lang' and in_array($from_id, $admin_user_id) and $user['user_id'] == true){
    bot('sendmessage',[
            'chat_id'=>$from_id,
            'text'=>"🇺🇸 Please select your language to continue\n🇸🇦 يُرجى اختيار لغتك للمتابعة\n🇮🇷 لطفا زبان خودرا انتخاب کنید\n",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    [['text'=>"🇮🇷",'callback_data'=>"setlang|1"]],
                    [['text'=>"🇺🇸",'callback_data'=>"setlang|2"],['text'=>"🇸🇦",'callback_data'=>"setlang|3"]],
                ]
            ])
        ]);
    exit;
}
elseif(strpos($data,"setlang|" ) !== false ){
    $exit    = explode("|",$data);
    $lang_id = $exit[1];

    switch ($lang_id) {
        case '1':
            $lang_name = 'fa';
            break;
        case '2':
            $lang_name = 'en';
            break;
        case '3':
            $lang_name = 'ar';
            break;
        default:
            $lang_name = 'en';
            break;
    }
    $connect->query("UPDATE `users` SET `lang` = '$lang_name' WHERE `user_id` = '$fromid' LIMIT 1");

    $lang_mg = new LangManager($lang_name);

    bot('EditMessageText',[
        'chat_id'             => $chatid,
        'message_id'          => $messageid,
        'text'                => "{$lang_mg->get('change_lang_welcome_text')}",
        'reply_to_message_id' =>$message_id,
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [['text'=>"🤝",'url'=>"https://github.com/hojjatjh"]],
            ]
        ])
    ]);
}

// Bot language management
$lang = new LangManager($user['lang']);

// Bot menu
if (in_array($from_id, $admin_user_id)) {
    $home = json_encode([
        'keyboard'=>[
            [['text'=>"Developer"]],
            [['text'=>$lang->get('open_admin_panel_btn')]],
        ],
        'resize_keyboard'=>true,
    ]);
}else{
    $home = json_encode([
        'keyboard'=>[
            [['text'=>"Developer"]],
        ],
        'resize_keyboard'=>true,
    ]);
}
$admin_menu = json_encode([
    'keyboard'=>[
        [['text'=>"Developer"]],
    ],
    'resize_keyboard'=>true,
]);


//============================================ Bot logic
if($text == "/start" && $tc == 'private'){
    bot('sendmessage',[
        'chat_id'=>$from_id,
        'text'=>"👋 Hello my dear friend!\nWelcome to SlideCastBot!\n\n❓ How can I help you today?\n➖➖➖➖➖➖➖➖➖\n👋 سلام دوست خوب من!\n  به ربات SlideCastBot خوش اومدی!  \n\n❓ چه کمکی می‌تونم بهت بکنم؟  ",
        'reply_markup'=>$home,
    ]);
}

elseif($text == 'Developer' && $tc == 'private'){
    bot('sendmessage',[
        'chat_id'=>$from_id,
        'text'=>"👨‍💻 توسعه‌دهنده / Developer\n\n🇮🇷 فارسی:\nسلام! من حجت جهان‌پور هستم، توسعه‌دهنده این ربات\nاگر میخواهید سورس کد این پروژه را ببینید به گیت‎هاب من مراجعه کنید\n\n🇬🇧 English:\nHi! I'm Hojjat Jahānpour, the developer of this bot.\nIf you want to check out the source code of this project, please visit my GitHub.\n\nhttps://github.com/hojjatjh",
        'reply_markup'=>$home,
    ]);
}

elseif($text == $lang->get('open_admin_panel_btn') and $tc == 'private' and in_array($from_id,$admin_user_id)){
    bot('sendmessage',[
        'chat_id'=>$from_id,
        'text'=>"{$lang->get('admin_welcome_to_menu')}",
        'reply_markup'=>$home,
    ]);
}