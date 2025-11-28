<?php
class LangManager{
    private string $defaultLang = 'fa';
    private string $currentLang;
    private array $translations = [];

    public function __construct(string $lang = 'fa'){
        $this->currentLang = $lang;
        $this->loadTranslations();
    }

    private function loadTranslations(): void{
        $this->translations = [
            'fa' => [
                'open_admin_panel_btn'          => '⚙️ پنل مدیریت',
                'change_lang_welcome_text'      => '✅ همکنون میتوانید از ربات استفاده کنید',
                'admin_welcome_to_menu'         => '• به پنل مدیریت ربات خودتون خوش اومدید',
            ],
            'en' => [
                'open_admin_panel_btn'          => '⚙️ Admin Panel',
                'change_lang_welcome_text'      => '✅ You can use the robot now',
                'admin_welcome_to_menu'         => '• Welcome to your robots admin panel.',
            ],
            'ar' => [
                'open_admin_panel_btn'          => '⚙️ لوحة الإدارة',
                'change_lang_welcome_text'      => '✅يمكنك الآن استخدام الروبوت.',
                'admin_welcome_to_menu'         => '• مرحبًا بك في لوحة إدارة الروبوت الخاص بك.',
            ]
        ];
    }

    public function setLang(string $lang): void{
        $this->currentLang = $lang;
    }

    public function get(string $key): string{
        return $this->translations[$this->currentLang][$key]
            ?? $this->translations[$this->defaultLang][$key]
            ?? "[$key]";
    }
}
