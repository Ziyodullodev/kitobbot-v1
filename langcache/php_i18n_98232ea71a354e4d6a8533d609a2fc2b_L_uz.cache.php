<?php class L {
const start = '<b>@Top_Kitobbot Sizga istalgan turdagi kitoblarni topishda yordam beradi!</b>

/top - Eng ko\'p yuklab olingan kitoblar.
/lang - Tilni o\'zgartirish.
/about - Bot haqida.

<i>🔎 Kerakli kitob nomini yozing...</i>';
const about_text = '🔍Botning maqsadi - Foydalanuvchilarga kerakli kitoblarni taqdim etish.

biror muammoga duch kelsangiz @ZiyoDev ga murojaat qiling!';
const choice_lang = '🇺🇿Bot tilini tanlang.\n🇷🇺Выберите язык бота.';
const total_result_text = 'Jami: {total}-ta natija';
const write_more_three = 'Kechirasiz 3-tadan koproq so\'z kiriting!';
const top_searching_books = 'Top 10-ta ko\'p qidirilgan kitoblar';
const no_data_search = '😕 Topilmadi.
Iltimos siz yuborayotgan matndi aniqroq yozib yuboring
Misol uchun: O\'tkan kunlar';
const no_data_other = 'Boshqa natija yo\'q';
const you_first_page = 'Siz 1-chi saxifadasiz';
const update_bot = 'Botda yangilanishlar ketypati birozdan song ishlatib koring...\nnoqulaylik uchun uzur soraymiz !';
const update_text = 'yangilanish olib borilmoqda...';
public static function __callStatic($string, $args) {
    return vsprintf(constant("self::" . $string), $args);
}
}
function L($string, $args=NULL) {
    $return = constant("L::".$string);
    return $args ? vsprintf($return,$args) : $return;
}