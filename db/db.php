<?php

class Database {
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
        $this->db->query("UPDATE `users` SET `lang`='$lang' WHERE `chat_id`='$cid'");
        }

    function edit_search($cid, $text){
        $this->db->query("UPDATE `users` SET `search`= '{$text}' WHERE chat_id = '{$cid}'");
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
        $today = date('Y-m-d');
        $buoy = date('Y-m');
        $bugun = $this->db->query("SELECT id FROM `users` WHERE `created_at` = '$today'")->num_rows;
        $use = $this->db->query("SELECT id FROM `users` WHERE `last_active` = '$today'")->num_rows;
        $buoy = $this->db->query("SELECT id FROM `users` WHERE `created_at` like '$buoy-__'")->num_rows;
        $text = "Jami foydalanuvchilar: ".$users."\nJami kitoblar: ".$books."\nBugun botni ishlatganlar: {$use}"."\nBugun botga kirganlar: ".$bugun."\nBu oy botga kirganlar: ".$buoy;
        // "\nBugun botni ishlatganlar: ".$use
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
    
    function latin_to_cyrillic($string) {
        $cyrillic = array(
            'a' => 'а', 'b' => 'б', 'v' => 'в', 'g' => 'г', 'd' => 'д', 'e' => 'е', 'yo' => 'ё', 'j' => 'ж', 'z' => 'з', 'i' => 'и', 'iy' => 'й', 'k' => 'к', 'q' => 'қ', 'l' => 'л', 'm' => 'м', 'n' => 'н', 'o' => 'о', 'p' => 'п', 'r' => 'р', 's' => 'с', 't' => 'т', 'u' => 'у', 'f' => 'ф', 'x' => 'х', 'ch' => 'ч', 'sh' => 'ш', 'c' => 'ц', 'h' => 'ҳ', 'e\'h' => 'э', 'yu' => 'ю', 'ya' => 'я'
        );
        return str_replace(array_keys($cyrillic), array_values($cyrillic), $string);
    }
    

    function get_books_with_search_names($num) {
        $stmt = $this->db->prepare("SELECT id, book_name FROM books WHERE id > 0 LIMIT ?");
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
