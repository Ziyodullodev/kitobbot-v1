<?php

$text = $updates['message']['text'] ?? null;
$firstname = $updates['message']['from']['first_name'] ?? null;
$lastname = $updates['message']['from']['last_name'] ?? null;
$cid = $updates['message']['chat']['id'] ?? null;
$type = $updates['message']['chat']['type'] ?? null;
$mid = $updates['message']['message_id'] ?? null;
$reply = $updates['message']['reply_to_message']['text'] ?? null;
$user = $updates['message']['from']['username'] ?? null;
$user_data = $db->get_user($cid);

$db->set_active($cid);
$language = $user_data['lang'] ?? "uz";


if ($text == "/start") {
    if ($user_data['id'] > 0) {
        $tg->send_message(L::start);
    } else {
        $tg->set_inlineKeyboard([["text" => "🇺🇿O`zbek tili", "callback_data" => "uz"], ["text" => "🇷🇺Русский язык", "callback_data" => "ru"]])
        ->send_message(L::choice_lang);
        $db->set_user($cid, $firstname . " " . $lastname, "uz");
    }
} elseif ($text == "/about") {
    $tg->send_message(L::about_text);
} elseif ($text == "/me") {

    $tg->send_message(L::update_text);
} elseif ($text == "/stat" and $cid == $admin) {
    $stat = $db->statistika();
    $tg->send_message($stat);
// } elseif ($text == "/stats") {
//     $vil = [
//         "Farg'ona" => 'fergana',
//         "Jizzax" => 'fergana',
//         "Andijon" => 'fergana',
//         "Namangan" => 'fergana',
//         "Qashqadaryo" => 'fergana',
//         "Farg'ona" => 'fergana',
//         "Farg'ona" => 'fergana',
//     ];
//     $tg->send_message($stat);
    
} elseif ($text == "/top") {
    $result = $db->get_book_top();
    $i = 0;
    $c = 5;
    for ($i = 0; $i < count($result); $i++) {
        $book_id = $result[$i][0];
        $book_name = $result[$i][1];
        $cap = $cap . ($i + 1) . ") " . $book_name . "\n";
        $keytype = ['text' => ($i + 1), 'callback_data' => $book_id];
        $k[floor($i / $c)][$i % $c] = $keytype;
    }
    $keytype = ['text' => "❌", 'callback_data' => "del"];
    $k[floor(2)][0] = $keytype;
    $tg
    ->set_inlineKeyboard($k)
    ->send_message(L::top_searching_books."\n\n{$cap}".'sa');
} elseif ($text == "/lang") {
    $tg->set_inlineKeyboard([
        [["text" => "🇺🇿O`zbek tili", "callback_data" => "uz"], ["text" => "🇷🇺Русский язык", "callback_data" => "ru"]],
        [["text" => "❌", "callback_data" => "del"]]
    ])
    ->send_message(L::choice_lang);
} else {

    $text1 = str_replace("'", "", $text);
    $son = strlen($text1);
    if ($son < 3) {
        $tg->send_message(L::write_more_three);
    } else {
        $tg->send_message("🔍");
        $count_book = $db->count_book($text);
        $msg = $text;
        if ($count_book == 0) {
            $text1 = explode(" ", $text);
            $son1 = count($text1);
            $src = "book_name LIKE '%{$text1[0]}%'";
            if ($son1 != 1) {
                for ($ind = 1; $ind < $son1; $ind++) {
                    if (strlen($text1[$ind]) < 5) {
                        continue;
                    }
                    $src = $src . " or book_name LIKE '%{$text1[$ind]}%'";
                }
            }
            
            $text = "SELECT id, book_name, book_type
                FROM books
                WHERE " . $src;
            $size1 = "SELECT id, book_name, book_type
                FROM books
                WHERE " . $src;
            $size = $db->count_book_for_nodata($size1);
            if ($size == 0) {
                $tg->delete_message($mid + 1);
                $tg->send_message(L::no_data_search);
            } else {
                $db->edit_search($cid, $msg);
                $key = piecepage($text . "LIMIT 7", $size);
                $tg->delete_message($mid + 1);
                $tg->set_inlineKeyboard($key)
                ->send_message(str_replace("{total}", $size, L::total_result_text));
                // file_put_contents("users/$cid.search", "{$msg}");
            }
        } else {
            $key = firstpage($text, $count_book);
            $tg->delete_message($mid + 1);
            $tg->set_inlineKeyboard($key)->send_message(str_replace("{total}", $count_book, L::total_result_text));
            
            // file_put_contents("searchs/$cid.search", "{$text}");
            // $tg->send_photo($images[0], "Jami: {$count_book}-ta natija", $cid, null, $key);
        }
    }
}


function firstpage($text, $size)
{
    global $db;
    $books = $db->get_books_by_page(0, $text);

    $all1 = $size % 7;
    $all = intval($size / 7);

    if ($all1 > 0) {
        $all += 1;
    }
    $c = 1;
    for ($i = 0; $i < count($books); $i++) {
        $id = $books[$i]['id'];
        $name = $books[$i]['book_name'];
        $type = $books[$i]['book_type'];
        if ($type == "audio/mp3" or $type == "audio/mpeg") {
            $keytype = ['text' => "🎙 {$name}", 'callback_data' => "book={$id}"];
            $k[floor($i / $c)][$i % $c] = $keytype;
            continue;
        }
        $keytype = ['text' => "📔 {$name}", 'callback_data' => "book={$id}"];
        $k[floor($i / $c)][$i % $c] = $keytype;
    }
    $keytype = ['text' => "⏮", 'callback_data' => "old={$text}=-7={$i}={$size}"];
    $k[floor($i)][0] = $keytype;
    $keytype = ['text' => ("1" . "/" . $all), 'callback_data' => "non"];
    $k[floor($i)][1] = $keytype;
    $keytype = ['text' => "⏭", 'callback_data' => "next={$text}=-7={$i}={$size}"];
    $k[floor($i)][2] = $keytype;
    $keytype = ['text' => "❌", 'callback_data' => "del"];
    $k[floor($i + 1)][0] = $keytype;
    return $k;
}
function piecepage($text, $size)
{
    global $db;
    $books = $db->Noanswer($text);
    $all1 = $size % 7;
    $all = intval($size / 7);

    if ($all1 > 0) {
        $all += 1;
    }
    $c = 1;
    for ($i = 0; $i < count($books); $i++) {
        $id = $books[$i]['id'];
        $name = $books[$i]['book_name'];
        $type = $books[$i]['book_type'];
        if ($type == "audio/mp3" or $type == "audio/mpeg") {
            $keytype = ['text' => "🎙 {$name}", 'callback_data' => "book={$id}"];
            $k[floor($i / $c)][$i % $c] = $keytype;
            continue;
        }
        $keytype = ['text' => "📔 {$name}", 'callback_data' => "book={$id}"];
        $k[floor($i / $c)][$i % $c] = $keytype;
    }
    $keytype = ['text' => "⏮", 'callback_data' => "old=search=-7={$i}={$size}"];
    $k[floor($i)][0] = $keytype;
    $keytype = ['text' => ("1" . "/" . $all), 'callback_data' => "non"];
    $k[floor($i)][1] = $keytype;
    $keytype = ['text' => "⏭", 'callback_data' => "next=search=-7={$i}={$size}"];
    $k[floor($i)][2] = $keytype;
    $keytype = ['text' => "❌", 'callback_data' => "del"];
    $k[floor($i + 1)][0] = $keytype;
    return $k;
}
