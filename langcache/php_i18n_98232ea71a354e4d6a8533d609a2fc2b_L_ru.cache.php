<?php class L {
const start = '<b>@Top_Kitobbot Поможет найти любую книгу!</b>

/top - Самые скачиваемые книги.
/lang - Сменить язык.
/about - О боте.

<i>🔎 Напишите название нужной книги...</i>';
const about_text = '🔍Цель бота - предоставить пользователям необходимые книги.

Свяжитесь с @Anonymlik_bot для всех видов услуг по созданию ботов!';
const write_more_three = 'Извините, введите более 3 слов!';
const choice_lang = '🇺🇿Bot tilini tanlang.\n🇷🇺Выберите язык бота.';
const total_result_text = 'Всего: <b> {total} </b>результатов';
const top_searching_books = '10 самых популярных книг';
const no_data_search = '😕 Не найдено.
Пожалуйста, укажите текст, который вы отправляете
Например: Дни минувшие';
const no_data_other = 'Других результатов нет';
const you_first_page = 'Вы находитесь на странице 1';
const update_bot = 'Попробуйте через некоторое время использовать ключ обновления бота...\nПриносим извинения за неудобства!';
const update_text = 'обновление...';
public static function __callStatic($string, $args) {
    return vsprintf(constant("self::" . $string), $args);
}
}
function L($string, $args=NULL) {
    $return = constant("L::".$string);
    return $args ? vsprintf($return,$args) : $return;
}