<?php

$qid = $updates['callback_query']['id'] ?? null; 
$data = $updates['callback_query']['data'] ?? null;
$text = $updates['callback_query']['message']['text'] ?? null;
$cid = $updates['callback_query']['message']['chat']['id'] ?? null;
$type = $updates['callback_query']['message']['chat']['type'] ?? null;
$mid = $updates['callback_query']['message']['message_id'] ?? null;
$user_data = $db->get_user($cid);

if ($data=="del"){
    $tg->delete_message();
}elseif ($data == "uz" or $data == "ru") {
    $db->edit_lang($cid, $data);
    $tg->edit_message(L::start);
}else{
    $cp = explode("=", $data);
    if ($cp[1]=="search"){
        if ($cp[0]=="next"){
            if ($cp[3] >= $cp[4]) {
                $tg->send_answer(L::no_data_other, true);
                exit();
            }
            $text = $user_data['search'];
            $key = searchpagination($cp[2], $cp[3], $cp[4], "next", $text);
            $tg->set_inlineKeyboard($key)
            ->edit_message($text);
            }elseif ($cp[0]=="old"){
            if ($cp[2] < 0) {
                $tg->send_answer(L::you_first_page, true);
                exit();
            }
            $text = $user_data['search'];
            $key = searchpagination($cp[2], $cp[3], $cp[4], "old", $text);
            $tg->set_inlineKeyboard($key)
            ->edit_message($text);
        }
        exit();
    }
        if ($cp[0]=="next"){
        if ($cp[3] >= $cp[4]) {
            $tg->send_answer(L::no_data_other, true);
            exit();
        }
        $key = pagination($cp[2], $cp[3], $cp[4], "next", $cp[1]);
        $tg->set_inlineKeyboard($key)
            ->edit_message($text);
        }elseif ($cp[0]=="old"){
        if ($cp[2] < 0) {
            $tg->send_answer(L::you_first_page, true);
            exit();
        }
        $key = pagination($cp[2], $cp[3], $cp[4], "old", $cp[1]);
        $tg->set_inlineKeyboard($key)
            ->edit_message($text);
    }elseif ($cp[0]=="book"){
        $book = $db->get_book($cp[1]);
        $ol = $book['book_size'] / 1024;
        $ol /= 1024;
        $size = number_format($ol, 1);
        $icon = "📔";
        if ($book['book_type'] == 'audio/mpeg' or $book['book_type'] == 'audio/mp3') {
            $icon = "🎙";
        }

        $text = "{$icon}<b>{$book['book_name']}</b>\n\n<i>💾 {$size} MB\n📥 {$book['book_top']}\n\n</i>✅ @Top_Kitobbot";;
        $tg->send_chatAction('upload_document')
        ->set_inlineKeyboard([[["text" => "🚫", "callback_data" => "del"]]])
        ->request('sendDocument',[
            'chat_id' => $cid,
            'message_id' => $mid,
            'document' => $book['file_link'],
            'caption' => $text,
            'parse_mode' => 'html'
        ]);
    }
}

function pagination($b, $k, $size,$page="first", $text)
{
      global $db;
      if ($page == "old"){
        $s = $b;
        $b = $b - 7;
    }else{
        $s = $k;
        $b = $k - 7;
      }
      $books = $db->get_books_by_page($s, $text);
      $keys = [];
      for($i=0;$i<count($books);$i++){
        $s++;
        $id = $books[$i]['id'];
        $name = $books[$i]['book_name'];
        $type = $books[$i]['book_type'];
        if ($type == "audio/mp3" or $type == "audio/mpeg") {
            $keys[] = [
                ['text' => "🎙 {$name}", 'callback_data' => "book={$id}"]
            ];
            continue;
        }
        $keys[] = [
            ['text' => "📔 {$name}", 'callback_data' => "book={$id}"]
        ];
    }
      $all1 = $size % 7;
        $all = intval($size / 7);

        if ($all1 > 0 ){
            $all += 1;
        }
    if ($s%7!=0){
        $n = intval($s/7)+1;
      }else{
        $n = intval($s/7);
      }
      $keys[] = [
        ['text' => "⏮", 'callback_data' => "old={$text}={$b}={$s}={$size}"],
        ['text' => ("$n"."/".$all), 'callback_data' => "non"],
        ['text' => "⏭", 'callback_data' => "next={$text}={$b}={$s}={$size}"]
    ];
    $keys[] = [
        ['text' => "❌", 'callback_data' => "del"]
    ];
      
      return $keys;

}
function searchpagination($b, $k, $size,$page="first", $text)
{
      global $db;
      if ($page == "old"){
        $s = $b;
        $b = $b - 7;
    }else{
        $s = $k;
        $b = $k - 7;
      }
      $books = $db->get_page_by_search($s, $text);
      $keys = [];
      for($i=0;$i<count($books);$i++){
        $s++;
        $id = $books[$i]['id'];
        $name = $books[$i]['book_name'];
        $type = $books[$i]['book_type'];
        if ($type == "audio/mp3" or $type == "audio/mpeg") {
            $keys[] = [
                ['text' => "🎙 {$name}", 'callback_data' => "book={$id}"]
            ];
            continue;
        }
        $keys[] = [
            ['text' => "📔 {$name}", 'callback_data' => "book={$id}"]
        ];
    }
      $all1 = $size % 7;
        $all = intval($size / 7);

        if ($all1 > 0 ){
            $all += 1;
        }
    if ($s%7!=0){
        $n = intval($s/7)+1;
      }else{
        $n = intval($s/7);
      }
      $keys[] = [
        ['text' => "⏮", 'callback_data' => "old=search={$b}={$s}={$size}"],
        ['text' => ("$n"."/".$all), 'callback_data' => "non"],
        ['text' => "⏭", 'callback_data' => "next=search={$b}={$s}={$size}"]
    ];
    $keys[] = [
        ['text' => "❌", 'callback_data' => "del"]
    ];
      return $keys;

}
?>