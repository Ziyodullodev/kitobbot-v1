<?php



class Main
{

    public $tg;
    public $db;
    public function __construct($tg, $db)
    {
        $this->tg = $tg;
        $this->db = $db;
    }

    public function main_keyboard(string $lang)
    {
        if ($lang == "uz") {
            $keys = [
                [
                    ['text' => "🛍 Kategoryalar", 'callback_data' => "category"]
                ],
                [
                    ['text' => "❤️ Yoqtirganlar", 'callback_data' => "likes"],
                    ['text' => "🛒 Savatcha", 'callback_data' => "card"]
                ],
                [
                    ['text' => "ℹ️ Bot haqida", 'callback_data' => "about"],
                    ['text' => "📃 Xaridlar tarixi", 'callback_data' => "orders"]
                ],
                [
                    ['text' => "🔍 Qidirish", 'callback_data' => "search"]
                ],
                [
                    ['text' => "👤 Profil & Sozlamalar", 'callback_data' => "profile"]
                ],
            ];
        } elseif ($lang == "ru") {
            $keys = [
                [
                    ['text' => "🛍 Категории", 'callback_data' => "category"]
                ],
                [
                    ['text' => "❤️ Нравится", 'callback_data' => "likes"],
                    ['text' => "🛒 Корзина", 'callback_data' => "card"]
                ],
                [
                    ['text' => "ℹ️ О боте", 'callback_data' => "about"],
                    ['text' => "📃История покупок", 'callback_data' => "orders"]
                ],
                [
                    ['text' => "🔍 Поиск", 'callback_data' => "search"]
                ],
                [
                    ['text' => "👤 Профиль и настройки", 'callback_data' => "profile"]
                ],
            ];
        } else {
            $keys = [
                [
                    ['text' => "🛍 Категорялар", 'callback_data' => "category"]
                ],
                [
                    ['text' => "❤️ Ёқтирганлар", 'callback_data' => "likes"],
                    ['text' => "🛒 Саватча", 'callback_data' => "card"]
                ],
                [
                    ['text' => "ℹ️ Бот ҳақида", 'callback_data' => "about"],
                    ['text' => "📃 Харидлар тарихи", 'callback_data' => "orders"]
                ],
                [
                    ['text' => "🔍 Қидириш", 'callback_data' => "search"]
                ],
                [
                    ['text' => "👤 Профил & Созламалар", 'callback_data' => "profile"]
                ],
            ];
        }

        return $keys;
    }

    private function login_keyboard($lang)
    {
        if ($lang == "uz") {
            $keys = [
                [["text"=>"📲 Telefon raqamni yuborish","request_contact"=>true]],
            ];
        } elseif ($lang == "ru") {
            $keys = [
                [["text"=>"📲 Отправить номер телефона","request_contact"=>true]],
            ];
        } else {
            $keys = [
                [["text"=>"📲 Телефон рақамни юбориш","request_contact"=>true]],
            ];
        }
        return $keys;
    }

    public function lang_keyboard()
    {
        return [
            [
                ["text" => "O'zbekcha", 'callback_data' => "uz"],
                ["text" => "Russian", 'callback_data' => "ru"],
            ],
            [
                ["text" => "Krilcha", 'callback_data' => "kr"]
            ]
        ];
    }

    public function startHandler($text)
    {
        $keyboard = $this->lang_keyboard();
        return $this->tg->set_inlineKeyboard($keyboard)->send_message($text[0]);
    }
    public function main_Handler($text = 'lang')
    {
        $user = getuserprofile();
        $lang = $user['lang'];
        $keyboard = $this->main_keyboard($lang);
        return $this->tg->set_inlineKeyboard($keyboard)->send_message(L::menu_text);
    }
    public function mainHandler($text = 'non')
    {
        $user = getuserprofile();
        $lang = $user['lang'];
        $keyboard = $this->main_keyboard($lang);
        return $this->tg->set_inlineKeyboard($keyboard)->edit_message(L::menu_text);
    }

    public function langedit($data)
    {
        $user = getuserprofile();
        $lang = $user['lang'];
        $keyboard = $this->main_keyboard($lang);
        $this->db->update_user('language', $data);
        get_local_base()->updateUser("`lang` = '{$data}'");
        return $this->tg->set_inlineKeyboard($keyboard)->edit_message(L::edit_success_language);
    }
    public function loginHandler($text)
    {
        $keyboard = $this->login_keyboard($text[0]);
        return $this->tg->set_replyKeyboard($keyboard)->send_message($text[1]);
    }
    public function textunknowHandler($text = 'non')
    {
        $user = getuserprofile();
        $lang = $user['lang'];
        $keyboard = $this->main_keyboard($lang);
        return $this->tg->set_inlineKeyboard($keyboard)->send_message(L::menu_text);
    }

    public function dataunknowHandler($text)
    {
        $keyboard = $this->main_keyboard($text[0]);
        return $this->tg->set_inlineKeyboard($keyboard)->edit_message($text[1]);
    }
}
