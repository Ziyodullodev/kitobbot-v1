<?php

class MysqlConstructor {
    private $db;
    private $logger;
    function __construct($logger = null){
        $this->logger = $logger;
        $this->connect();
        
    }
    // Properties
    function connect(){
        $host = "213.32.7.133"; // в 90% случаев это менять не надо
        $password = "bI1mX8yP9w";
        $username = "search";
        $databasename = "search";
        setlocale(LC_ALL,"ru_RU.UTF8");
        $db = new mysqli($host, $username, $password, $databasename, 3306);
        $db->set_charset('utf8mb4');
        if ($db->connect_errno) {
            $this->logger->send_message($db->connect_error, 848796050);
            throw new Exception("Error Processing Request", 1);
            
        }
        $this->db = $db;
    }
    // Users
    function get_user($id){
        $res = $this->db->query("SELECT * FROM `users` WHERE `chat_id`='$id'");
        return ($res->num_rows > 0) ? $res->fetch_assoc() : false;
        }
    function set_user($id, $name, $lang){
        $this->db->query("INSERT INTO `users` (`chat_id`,`full_name`,`lang`,`created_at`,`last_active`) VALUES ('$id', '$name','$lang',NOW(), NOW())");
        }
    function edit_lang($cid, $lang){
        $this->db->query("UPDATE `users` SET `lang`='$lang' WHERE `userid`='$cid'");
        }
    function edit_search($cid, $text){
        $this->db->query("UPDATE `users` SET `search`='$text' WHERE `chat_id`='$cid'");
        }   
    function set_active($cid){
        $this->db->query("UPDATE `users` SET `last_active`= NOW() WHERE `chat_id`='$cid'");
        }
    // books
    function get_book_top(){
        $res = $this->db->query("SELECT `id`, `book_name`
        FROM `books` 
        ORDER BY `book_top` DESC 
        LIMIT 10");
        return ($res->num_rows > 0) ? $res->fetch_all() : false;
        }


    function get_books_by_page($page_num, $name) {
        $stmt = $this->db->prepare("SELECT id, book_name, book_type
            FROM books
            WHERE book_name LIKE CONCAT('%', ?, '%')
            LIMIT ?, 7");
    
        $stmt->bind_param("si", $name, $page_num);
        $stmt->execute();
    
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
        
    function count_book($text){
        $stmt = $this->db->query("SELECT id FROM `books` WHERE `book_name` like '%{$text}%'");
        $count = $stmt->num_rows;
        return $count;
    }

    function get_book($id){
        $res = $this->db->query("SELECT * FROM `books` WHERE `id`='$id'");
        $this->book_top($id);
        return $res->fetch_assoc();
    }
    
    function book_top($id){
        $this->db->query("UPDATE `books` SET book_top=book_top+1 WHERE id=$id");
        }
        
    function statistika(){
        $books = $this->db->query("SELECT id FROM `books`")->num_rows;
        $users = $this->db->query("SELECT id FROM `users`")->num_rows;
        // $bugun = $this->db->query("SELECT id FROM `users` WHERE `created_at` = NOW()")->num_rows;
        // $use = $this->db->query("SELECT id FROM `users` WHERE `last_active` = NOW()")->num_rows;
        // $sqlt3 = mysqli_query($db, "SELECT ID FROM `Users` WHERE `created_at` like '__-$m-$y'");
        // $buoy = mysqli_num_rows($sqlt3);
        $text = "Jami foydalanuvchilar: ".$users."\nJami kitoblar: ".$books;
        // \nBugun botga kirganlar: ".$bugun."\nBugun botni ishlatganlar: ".$use
        return $text;
    }
    function Noanswer($text){
    $result = $this->db->query($text);
    return $result->fetch_all(MYSQLI_ASSOC);
    }
    function get_page_by_search($num, $text){
        $result = $this->db->query($text."LIMIT {$num}, 7");
        return $result->fetch_all(MYSQLI_ASSOC);
        }
    function count_book_for_nodata($text){
        $stmt = $this->db->query($text);
        $count = $stmt->num_rows;
        return $count;
    }
    function change_get($num) {
        $stmt = $this->db->query("SELECT id, book_name, book_type
            FROM books
            WHERE id > 0
            LIMIT $num, 50");
        return $stmt->fetch_all(MYSQLI_ASSOC);
    }
    function update_book_name($id, $new_name) {
        $stmt = $this->db->prepare("UPDATE books SET search_name = ? WHERE id = ?");
        $stmt->bind_param("si", $new_name, $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
    
    function latin_to_cyrillic($str) {
    $map = array(
        'A' => 'А', 'B' => 'Б', 'C' => 'Ц', 'D' => 'Д', 'E' => 'Е', 'F' => 'Ф', 'G' => 'Г', 'H' => 'Ҳ', 'I' => 'И', 'J' => 'Ж', 'K' => 'К', 'L' => 'Л', 'M' => 'М', 'N' => 'Н', 'O' => 'О', 'P' => 'П', 'Q' => 'Қ', 'R' => 'Р', 'S' => 'С', 'T' => 'Т', 'U' => 'У', 'V' => 'В', 'W' => 'В', 'X' => 'Х', 'Y' => 'Й', 'Z' => 'З',
        'a' => 'а', 'b' => 'б', 'c' => 'ц', 'd' => 'д', 'e' => 'е', 'f' => 'ф', 'g' => 'г', 'h' => 'ҳ', 'i' => 'и', 'j' => 'ж', 'k' => 'к', 'l' => 'л', 'm' => 'м', 'n' => 'н', 'o' => 'о', 'p' => 'п', 'q' => 'қ', 'r' => 'р', 's' => 'с', 't' => 'т', 'u' => 'у', 'v' => 'в', 'w' => 'в', 'x' => 'х', 'y' => 'й', 'z' => 'з',
        'Ch' => 'Ч', 'CH' => 'Ч', 'ch' => 'ч', 'Sh' => 'Ш', 'SH' => 'Ш', 'sh' => 'ш', 'Ya' => 'Я', 'YA' => 'Я', 'ya' => 'я', 'Yo' => 'Ё', 'YO' => 'Ё', 'yo' => 'ё', 'Yu' => 'Ю', 'YU' => 'Ю', 'yu' => 'ю',
    );
    return strtr($str, $map);
}


    

    function get_books_with_search_names($num) {
        $stmt = $this->db->prepare("SELECT id, book_name FROM books WHERE id > 0 LIMIT ?, 250");
        $stmt->bind_param("i", $num);
        $stmt->execute();
        $books = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        foreach ($books as $book) {
            $id = $book['id'];
            $name = $book['book_name'];
            $search_name = $this->latin_to_cyrillic($name); // convert Cyrillic to Latin
            $book['search_name'] = $search_name; // add search name to book array
            $stmt2 = $this->db->prepare("UPDATE books SET search_name=? WHERE id=?");
            $stmt2->bind_param("si", $search_name, $id);
            $stmt2->execute(); // update search name in search_name table
        }
        return $books;
    }

    function cyr_to_lat($str) {
        $cyr_chars = ['а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'];
        $lat_chars = ['a', 'b', 'v', 'g', 'd', 'e', 'yo', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'kh', 'ts', 'ch', 'sh', 'shch', '', 'y', '', 'e', 'yu', 'ya'];
        return str_replace($cyr_chars, $lat_chars, mb_strtolower($str));
    }


}
function cyr_to_lat($str) {
    $cyr_chars = ['а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'];
    $lat_chars = ['a', 'b', 'v', 'g', 'd', 'e', 'yo', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'kh', 'ts', 'ch', 'sh', 'shch', '', 'y', '', 'e', 'yu', 'ya'];
    return str_replace($cyr_chars, $lat_chars, mb_strtolower($str));
}
$admin = '848796050'; // Admin ID
$token = '2009076458:AAEMGFIpy6lRIkJOby3sZKNR-19ULuL08pA';  //Bot token

function bot($method,$datas=[]){
global $token;
$url = "https://api.telegram.org/bot".$token."/".$method;
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
$res = curl_exec($ch);
if(curl_error($ch)){
var_dump(curl_error($ch));
}else{
return json_decode($res);
}
}


if (file_get_contents("ok.txt") == "ok"){
    
    $num = file_get_contents("num.txt");
    if ($num / 250 == 750){
        file_put_contents("ok.txt", "stop");
    }else{
        $a = new MysqlConstructor();
        $a->get_books_with_search_names($num);
        file_put_contents("num.txt", $num+250);
    }

}else{
    bot('sendmessage',[
            'chat_id'=>$admin,
            'text'=>"Stopped",
      ]);
}
