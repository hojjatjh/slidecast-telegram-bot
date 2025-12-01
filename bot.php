<?php
error_reporting(0);

// Import other files
require_once 'core/update.php';
require_once 'func/lang.php';
require_once 'func/functions.php';

// Receive updates to perform updates
$newData = file_get_contents("php://input");
file_put_contents('data.txt', $newData . "\n----------------------\n", FILE_APPEND);

// Admin language selection
if (in_array($from_id, $admin_user_id)){
    if($user['user_id'] != true or $user['lang'] == 'not_set'){
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

    $home_change_lang = json_encode([
        'keyboard'=>[
            [['text'=>"Developer"]],
            [['text'=>$lang_mg->get('open_admin_panel_btn')]],
        ],
        'resize_keyboard'=>true,
    ]);

    bot('sendmessage',[
        'chat_id'=>$fromid,
        'text'=>"{$lang_mg->get('start_message')}",
        'reply_markup'=>$home_change_lang,
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
// admin menu
$admin_menu = json_encode([
    'keyboard'=>[
        [['text'=>$lang->get('btn_admin_1')]],
        [['text'=>$lang->get('btn_admin_2')], ['text'=>$lang->get('btn_admin_3')]],
        [['text'=>"/start"]],
    ],
    'resize_keyboard'=>true,
]);
$back_btn = json_encode([
    'keyboard'=>[
        [['text'=>$lang->get('open_admin_panel_btn')]],
    ],
    'resize_keyboard'=>true,
]);


//============================================ Bot logic
if($text == "/start" && $tc == 'private'){
    if(in_array($from_id, $admin_user_id)){
        $connect->query("UPDATE `users` SET `step` = 'none' WHERE `user_id` = '$from_id' LIMIT 1");
    }
    bot('sendmessage',[
        'chat_id'=>$from_id,
        'text'=>"👋 Hello my dear friend!\nWelcome to SlideCastBot!\n\n❓ How can I help you today?\n────────────────────\n👋 سلام دوست خوب من!\nبه ربات SlideCastBot خوش اومدی!\n\n❓ چه کمکی می‌تونم بهت بکنم؟\n────────────────────\n👋 مرحباً صديقي العزيز!\nمرحباً بك في بوت SlideCastBot!\n\n❓ كيف يمكنني مساعدتك اليوم؟",
        'reply_markup'=>$home,
    ]);
    exit;
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
        'reply_markup'=>$admin_menu,
    ]);
    $connect->query("UPDATE `users` SET `step` = 'none' WHERE `user_id` = '$from_id' LIMIT 1");
    exit;
}

elseif($text == $lang->get('btn_admin_1') and $tc == 'private' and in_array($from_id,$admin_user_id)){
    bot('sendmessage',[
        'chat_id'      => $from_id,
        'text'         => "{$lang->get('text_1')}",
        'reply_markup' => $back_btn,
    ]);
    $connect->query("UPDATE `users` SET `step` = 'add_p_1' WHERE `user_id` = '$from_id' LIMIT 1");
    exit;
}

elseif ($user['step'] == 'add_p_1' && $text != $lang->get('btn_admin_1') && $tc == 'private') {
    if(isset($text) and $text != null and $text != ""){
        $user_text          = trim($text);
        $slug               = make_slug($user_text);
        $select_presentation= mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `presentation` WHERE `slug` = '$slug' LIMIT 1"));

        if ($select_presentation['presentation_id'] == true){
            bot('sendmessage',[
                'chat_id'      => $from_id,
                'text'         => "{$lang->get('text_2')}",
                'reply_markup' => $back_btn,
            ]);
            exit;
        }

        bot('sendmessage',[
            'chat_id'      => $from_id,
            'text'         => "{$lang->get('text_3')}",
            'reply_markup' => $back_btn,
        ]);

        $connect->query("UPDATE `users` SET `data` = '$user_text' WHERE `user_id` = '$from_id' LIMIT 1");
        $connect->query("UPDATE `users` SET `step` = 'add_p_2' WHERE `user_id` = '$from_id' LIMIT 1");
    }
}

elseif ($user['step'] == 'add_p_2' && $text != $lang->get('btn_admin_1') && $tc == 'private') {
    $title     = $user['data'];
    $slug      = make_slug($title);
    $user_text = trim($text);
    if(preg_match("/^[0-10-9]+$/", $user_text)) {
        bot('sendmessage',[
            'chat_id'      => $from_id,
            'text'         => "{$lang->get('text_5')}",
            'reply_markup' => $back_btn,
        ]);
        $connect->query("INSERT INTO `presentation` (`slug` , `title`, `channel_id`, `slide_count`, `created_by`, `message_id`) VALUES ('$slug' , '$title', '$user_text', '0', '$from_id', '0')");
        $connect->query("UPDATE `users` SET `step` = 'add_p_3' WHERE `user_id` = '$from_id' LIMIT 1");
        
        $select_new_presentation = mysqli_fetch_assoc(mysqli_query($connect, "SELECT `presentation_id` FROM `presentation` WHERE `slug` = '$slug' AND `created_by` = '$from_id' LIMIT 1"));
        $presentation_id = $select_new_presentation['presentation_id'];
        
        $connect->query("UPDATE `users` SET `data` = '$presentation_id' WHERE `user_id` = '$from_id' LIMIT 1");
    }else{
        bot('sendmessage',[
            'chat_id'      => $from_id,
            'text'         => "{$lang->get('text_4')}",
            'reply_markup' => $back_btn,
        ]);
        exit;
    }
}

elseif ($user['step'] == 'add_p_3' && $text != $lang->get('btn_admin_1') && $tc == 'private') {
    if(isset($update->message->photo) and !isset($update->message->media_group_id)){
        $photo   = $message->photo;
        $file_id = $photo[count($photo)-1]->file_id;
        bot('sendmessage',[
            'chat_id'      => $from_id,
            'text'         => "{$lang->get('text_7')}",
            'reply_markup' => $back_btn,
        ]);
        $connect->query("UPDATE `users` SET `step` = 'add_s_slide_for_presentation' WHERE `user_id` = '$from_id' LIMIT 1");
        $connect->query("UPDATE `users` SET `temp` = '1' WHERE `user_id` = '$from_id' LIMIT 1");
        $connect->query("UPDATE `users`SET `data` = CONCAT(IFNULL(`data`, ''), '^$file_id') WHERE `user_id` = '$from_id' LIMIT 1");
    }else{
        bot('sendmessage',[
            'chat_id'      => $from_id,
            'text'         => "{$lang->get('text_6')}",
            'reply_markup' => $back_btn,
        ]);
        exit;
    }
}

elseif ($text == '/finish' and $user['step'] == 'add_s_slide_for_presentation' and $tc == 'private' and in_array($from_id,$admin_user_id)){
    if($user['temp'] >= 2){
        $data                = $user['data'];
        $ex                  = explode("^", $data);
        $presentation_id     = $ex[0];
        $select_presentation = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `presentation` WHERE `presentation_id` = '$presentation_id' AND `created_by` = '$from_id' LIMIT 1"));
        $channel_id          = $select_presentation['channel_id'];
        if($select_presentation['presentation_id'] == true){
            if(isBotAdmin(API_KEY, "-$channel_id")){
                $p_title     = $select_presentation['title'];
                $slide_count = $user['temp'];

                bot('sendmessage',[
                    'chat_id'      => $from_id,
                    'text'         => "{$lang->get('text_13')}",
                ]);

                $parts = explode('^', $data);
                $p_id  = array_shift($parts);
                $slide_number = 1;
                foreach ($parts as $file_id) {
                    $file_id = $connect->real_escape_string($file_id);
                    $connect->query("INSERT INTO `slides` (`p_id`, `slide_n`, `file_id`) VALUES ('$p_id', '$slide_number', '$file_id')");
                    $slide_number++;
                }

                bot('sendmessage',[
                    'chat_id'      => $from_id,
                    'text'         => "{$lang->get('text_14')}",
                ]);

                $select_first_slide = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `slides` WHERE `p_id` = '$p_id' AND `slide_n` = '1' LIMIT 1"));
                $connect->query("UPDATE `presentation` SET `slide_count` = '$slide_count' WHERE `presentation_id` = '$p_id' LIMIT 1");
                
                $connect->query("UPDATE `users` SET `temp` = '0' WHERE `user_id` = '$from_id' LIMIT 1");
                $connect->query("UPDATE `users` SET `step` = 'none' WHERE `user_id` = '$from_id' LIMIT 1");
                $connect->query("UPDATE `users` SET `data` = null WHERE `user_id` = '$from_id' LIMIT 1");

                $id = bot('sendphoto',[
                    'chat_id'       => "-$channel_id",
                    'photo'         => $select_first_slide['file_id'],
                    'caption'       => "$p_title \n📑 (1/$slide_count)",
                    'reply_markup'  => json_encode([
                    'inline_keyboard'=>[
                            [['text'=>"▶️",'callback_data'=>"n|$p_id|2"]],
                            [['text'=>"🤝🤝",'url'=>"https://github.com/hojjatjh"]],
                        ]
                    ])
                ])->result;

                $mesage_id_post = $id->message_id;

                $connect->query("UPDATE `presentation` SET `message_id` = '$mesage_id_post' WHERE `presentation_id` = '$p_id' LIMIT 1");

                bot('sendmessage',[
                    'chat_id'      => $from_id,
                    'text'         => "{$lang->get('text_15')} \n -$channel_id",
                    'reply_markup' => $admin_menu,
                ]);
            }else{
                bot('sendmessage',[
                    'chat_id'      => $from_id,
                    'text'         => "{$lang->get('text_12')} -$channel_id",
                    'reply_markup' => $back_btn,
                ]);
                exit;
            }
        }else{
            bot('sendmessage',[
                'chat_id'      => $from_id,
                'text'         => "{$lang->get('text_8')}",
                'reply_markup' => $back_btn,
            ]);
            exit;
        }
    }else{
        bot('sendmessage',[
            'chat_id'      => $from_id,
            'text'         => "{$lang->get('text_11')}",
            'reply_markup' => $back_btn,
        ]);
        exit;
    }
}

elseif ($user['step'] == 'add_s_slide_for_presentation' && $text != $lang->get('btn_admin_1') && $text != "/start" && $text != "/finish" && $tc == 'private') {
    $data   = $user['data'];
    $ex     = explode("^", $data);
    $presentation_id = $ex[0];
    $select_presentation = mysqli_fetch_assoc(mysqli_query($connect, "SELECT `presentation_id` FROM `presentation` WHERE `presentation_id` = '$presentation_id' AND `created_by` = '$from_id' LIMIT 1"));
    
    if($select_presentation['presentation_id'] == true){
        if(isset($update->message->photo) and !isset($update->message->media_group_id)){
            $photo     = $message->photo;
            $file_id   = $photo[count($photo)-1]->file_id;
            $parts     = array_filter(explode("^", $data));  
            $s_count   = $user['temp'];
            $n_count   = $s_count + 1;

            bot('sendmessage',[
                'chat_id'      => $from_id,
                'text'         => "{$lang->get('text_9')} $n_count",
            ]);
            bot('sendmessage',[
                'chat_id'      => $from_id,
                'text'         => "{$lang->get('text_10')}",
                'reply_markup' => $back_btn,
            ]);

            $connect->query("UPDATE `users` SET `temp` = '$n_count' WHERE `user_id` = '$from_id' LIMIT 1");
            $connect->query("UPDATE `users`SET `data` = CONCAT(IFNULL(`data`, ''), '^$file_id') WHERE `user_id` = '$from_id' LIMIT 1");
        }else{
            bot('sendmessage',[
                'chat_id'      => $from_id,
                'text'         => "{$lang->get('text_6')}",
                'reply_markup' => $back_btn,
            ]);
            exit;
        }
    }else{
        bot('sendmessage',[
            'chat_id'      => $from_id,
            'text'         => "{$lang->get('text_8')}",
            'reply_markup' => $back_btn,
        ]);
        exit;
    }
}

elseif (strpos($data,"n|" ) !== false){
    $exit           = explode("|",$data);
    $p_id           = $exit[1]; // p_id
    $c_slide        = $exit[2]; // slide number
    $last_slide     = $c_slide - 1;
    $dd_next        = $c_slide + 1;

    if (in_array($fromid,$admin_user_id)){
        if(preg_match("/^[0-10-9]+$/", $c_slide) and preg_match("/^[0-10-9]+$/", $p_id)) {
            $select_p = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `presentation` WHERE `presentation_id` = '$p_id' LIMIT 1"));
            if($select_p['presentation_id'] == true){
                $select_slide  = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `slides` WHERE `p_id` = '$p_id' AND `slide_n` = '$c_slide' LIMIT 1"));
                $select_dd_sl  = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `slides` WHERE `p_id` = '$p_id' AND `slide_n` = '$dd_next' LIMIT 1"));
                $select_ls_sl  = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `slides` WHERE `p_id` = '$p_id' AND `slide_n` = '$last_slide' LIMIT 1"));

                $slide_count   = $select_p['slide_count'];
                $post_msg_id   = $select_p['message_id'];
                $post_title    = $select_p['title'];

                $keyboard_slide = [];
                if(!empty($select_ls_sl) && !empty($select_dd_sl)) {
                    $keyboard_slide = [
                        [['text'=>"◀️",'callback_data'=>"n|$p_id|$last_slide"], ['text'=>"▶️",'callback_data'=>"n|$p_id|$dd_next"]],
                        [['text'=>"🤝🤝",'url'=>"https://github.com/hojjatjh"]]
                    ];
                } elseif(!empty($select_dd_sl)) {
                    $keyboard_slide = [
                        [['text'=>"▶️",'callback_data'=>"n|$p_id|$dd_next"]],
                        [['text'=>"🤝🤝",'url'=>"https://github.com/hojjatjh"]]
                    ];
                } elseif(!empty($select_ls_sl)) {
                    $keyboard_slide = [
                        [['text'=>"◀️",'callback_data'=>"n|$p_id|$last_slide"], ['text'=>"🗑",'callback_data'=>"c|$p_id|1"]],
                        [['text'=>"🤝🤝",'url'=>"https://github.com/hojjatjh"]]
                    ];
                }

                if(!empty($select_slide)) {
                    bot('editMessageMedia', [
                        'chat_id' => "-".$select_p['channel_id'],
                        'message_id' => $post_msg_id,
                        'media' => json_encode([
                            'type' => 'photo',
                            'media' => $select_slide['file_id'],
                            'caption' => "$post_title \n📑 ($c_slide/$slide_count)",
                            'parse_mode' => 'HTML'
                        ]),
                        'reply_markup' => json_encode([
                            'inline_keyboard' => $keyboard_slide
                        ])
                    ]);
                } else {
                    bot('answercallbackquery', [
                        'callback_query_id' => $callback_query_id,
                        'text'              => "{$lang->get('text_17')}",
                        'show_alert'        => false
                    ]);
                }

            }else{
                bot('answercallbackquery', [
                    'callback_query_id' => $callback_query_id,
                    'text'              => "{$lang->get('text_16')}",
                    'show_alert'        => false
                ]);
            }
        }
    }
}

elseif (strpos($data,"c|" ) !== false){
    $exit           = explode("|",$data);
    $p_id           = $exit[1]; // p_id
    $c_slide        = $exit[2]; 

    if (in_array($fromid,$admin_user_id)){
        if(preg_match("/^[0-10-9]+$/", $c_slide) and preg_match("/^[0-10-9]+$/", $p_id)) {
            $select_p      = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `presentation` WHERE `presentation_id` = '$p_id' LIMIT 1"));
            $post_msg_id   = $select_p['message_id'];
            if($select_p['presentation_id'] == true){
                bot('deleteMessage', [
                    'chat_id'    => $chatid,      
                    'message_id' => $messageid
                ]);
                $connect->query("DELETE FROM `slides` WHERE `p_id` = $p_id");
                $connect->query("DELETE FROM `presentation` WHERE `presentation_id` = '$p_id' LIMIT 1");
            }
        }
    }
}