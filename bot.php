<?php
error_reporting(0);

// Import other files
require_once 'core/update.php';

// Bot menu
if (in_array($from_id, $admin_user_id)) {
    $home = json_encode([
        'keyboard'=>[
            [['text'=>"Developer"]],
            [['text'=>'⚙️ Admin Panel']],
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