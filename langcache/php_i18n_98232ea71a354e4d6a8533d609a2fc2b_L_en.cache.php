<?php class L {
const start = '*@Top_Kitobbot Sizga istalgan turdagi kitoblarni topishda yordam beradi!*

/top - Eng ko\'p yuklab olingan kitoblar.
/lang - Tilni o\'zgartirish.
/about - Bot haqida.

_🔎 Kerakli kitob nomini yozing..._';
const about_text = '🔍Botning maqsadi - Foydalanuvchilarga kerakli kitoblarni taqdim etish.

Barcha turdagi bot yasash xizmati uchun @Anonimlik_bot ga murojaat qiling!';
const write_more_three = 'Kechirasiz 3-tadan koproq so\'z kiriting!';
const top_searching_books = 'Top 10-ta ko\'p qidirilgan kitoblar';
const no_data_search = '😕 Topilmadi.
Iltimos siz yuborayotgan matndi aniqroq yozib yuboring
Misol uchun: O\'tkan kunlar';
public static function __callStatic($string, $args) {
    return vsprintf(constant("self::" . $string), $args);
}
}
function L($string, $args=NULL) {
    $return = constant("L::".$string);
    return $args ? vsprintf($return,$args) : $return;
}