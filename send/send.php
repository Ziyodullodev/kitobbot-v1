<?php
date_default_timezone_set("Asia/Tashkent");
$date = date("H:i d.m.Y");

if (file_get_contents("send.txt") == "ok") {
    ob_start();

    $sql = mysqli_connect("213.32.7.133", "search", "bI1mX8yP9w", "search");
    $token = "5010091658:AAE2JWScgpjmri4BuFX7VW6wSgvTv0nK95I";
    $admin = 848796050;

    function bot($method, $datas = [])
    {
        global $token;
        $url = "https://api.telegram.org/bot" . $token . "/" . $method;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
        $res = curl_exec($ch);
        if (curl_error($ch)) {
            print_r(curl_error($ch));
        } else {
            return json_decode($res);
        }
    }

    if (!file_exists("limit.txt")) {
        $a = "100";
    } else {
        $a = file_get_contents("limit.txt");
    }

    $mess = explode("=", file_get_contents("message.txt"));

    if (file_get_contents("reply.txt") != false) {
        $repl = file_get_contents("reply.txt");
    } else {
        $repl = $null;
    }

    $ty = file_get_contents('sendt.txt');
    $son = file_get_contents("son.txt");

    if ($ty == "us") {
        $result = mysqli_query($sql, "SELECT * FROM Users LIMIT $son,$a;");

        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $cid = $row['ChatID'];
            // $cid = 848796050;

            $content = ['chat_id' => $cid, 'from_chat_id' => $mess[0], 'message_id' => $mess[1], 'reply_markup' => $repl];

            $res = bot('copyMessage', $content);
            if ($res->ok) {
                $i++;
            }
        }

        $c = file_get_contents("umum.txt");
        if ($son > $c) {

            $get = file_get_contents("bordi.txt");
            $no = $c - $get;

            $txt = "📩 *Hammaga xabar yetkazildi‌‌. *

✳️ Umumiy: *$c*
✅ Yuborildi: *$get*
❎ Yuborilmadi: *$no*

⏰ `$date`";

            $content = ['chat_id' => $admin, 'text' => $txt, 'parse_mode' => "markdown"];

            bot('sendMessage', $content);

            unlink("send.txt");
            unlink("son.txt");
            unlink("bordi.txt");
            unlink("message.txt");
            unlink('reply.txt');
            unlink('sendt.txt');

            exit;
        }

        $get = file_get_contents("bordi.txt");
        file_put_contents("bordi.txt", $get + $i);

        $son = file_get_contents("son.txt");
        file_put_contents("son.txt", $son + $a);
    }
    // elseif($ty == 'gr'){
    //     $result = mysqli_query($sql,"SELECT * FROM groups LIMIT $son,$a;");

    //     $i = 1;
    // while($row = mysqli_fetch_assoc($result)){
    //     $cid = $row['group_id'];

    // $content = ['chat_id' => $cid, 'from_chat_id' => $mess[0], 'message_id' => $mess[1], 'reply_markup' => $repl];

    //    $res = bot('copyMessage',$content);
    // if($res->ok){
    //     $i++;
    // }
    // }

    // $c = file_get_contents("umum.txt");
    // if($son > $c){

    // $get = file_get_contents("bordi.txt");
    // $no = $c-$get;

    // $txt = "📩 *Hammaga xabar yetkazildi‌‌. *

    // ✳️ Umumiy: *$c*
    // ✅ Yuborildi: *$get*
    // ❎ Yuborilmadi: *$no*

    // ⏰ `$date`";

    // $content = ['chat_id' => $admin, 'text' => $txt, 'parse_mode' => "markdown"];

    // bot('sendMessage',$content);

    // unlink("send.txt");
    // unlink("son.txt");
    // unlink("bordi.txt");
    // unlink("message.txt");
    // unlink('reply.txt');
    // unlink('sendt.txt');

    // exit;

    // }

    // $get = file_get_contents("bordi.txt");
    // file_put_contents("bordi.txt",$get+$i);

    // $son = file_get_contents("son.txt");
    // file_put_contents("son.txt",$son+$a);
    // }

} else {
    echo 'Aktiv rassilka mavjud emas!';
}
