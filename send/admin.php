
<?php

///////////////////////////////////admin panels.//////////////////

if ($cid == $admin) {
    date_default_timezone_set("Asia/Tashkent");
    $date = date("H:i d.m.Y");
    if ($text == "/stop") {
        $s = file_get_contents('send/son.txt');
        $b = file_get_contents('send/bordi.txt');
        $f = $s - $b;

        $txt =  "🛑 *Hammaga xabar yuborish to'xtatildi.*

✳️ Umumiy: *$s*
✅ Yuborildi: *$b*
❎ Yuborilmadi: *$f*

⏰ `$date`";

        sendMessage($cid, $txt,'markdown');

        unlink("send/send.txt");
        unlink("send/son.txt");
        unlink("send/bordi.txt");
        unlink("send/message.json");
        exit;
    }
    if ($text == "/holat") {
        $s = file_get_contents('send/son.txt');
        $b = file_get_contents('send/bordi.txt');
        $jami = file_get_contents('send/umum.txt');
        $f = $s - $b;

        $txt =  " 📊*Habarlar yuborish xolati.*

✳️ Umumiy: *$jami*
✅ Yuborildi: *$b*
❎ Yuborilmadi: *$f*

⏰ `$date`";
        sendMessage($cid, $txt,'markdown');
        exit;
    }

    if (mb_stripos($text, "/limit") !== false) {
        $s = file_get_contents('send/son.txt');
        $b = file_get_contents('send/bordi.txt');
        $f = $s - $b;

        if (!file_exists("send/send.txt")) {
            $a = explode(" ", $text)[1];
            if (is_numeric($a)) {

                $txt = "🏷 Limit sozlandi. Daqiqasiga *$a*ta foydalanuvchiga xabar yuboraman!";
                sendMessage($cid, $txt, 'markdown');

                file_put_contents("send/limit.txt", $a);
                exit;
            } else {
                $txt = "☝️ *Kechirasiz hozir aktiv rassilka mavjud.*

✳️ Umumiy: *$s*
✅ Yuborildi: *$b*
❎ Yuborilmadi: *$f*

⏰ `$date`";

                sendMessage($cid, $txt,'markdown');
            }
        }
    }

    if (mb_stripos($text, "/limit") !== false) {
        $s = file_get_contents('send/son.txt');
        $b = file_get_contents('send/bordi.txt');
        $f = $s - $b;

        if (!file_exists("send/send.txt")) {
            $a = explode(" ", $text)[1];
            if (is_numeric($a)) {

                $txt = "🏷 Limit sozlandi. Daqiqasiga *$a*ta foydalanuvchiga xabar yuboraman!";
                sendMessage($cid, $txt, 'markdown');

                file_put_contents("send/limit.txt", $a);
                exit;
            } else {
                $txt = "☝️ *Kechirasiz hozir aktiv rassilka mavjud.*

✳️ Umumiy: *$s*
✅ Yuborildi: *$b*
❎ Yuborilmadi: *$f*

⏰ `$date`";

                sendMessage($cid, $txt,'markdown');
            }
        }
    }

    if ($text == "/send") {
        if (!file_exists("send/send.txt")) {

            $txt = "❇️ *Media fayl yoki xabar matnini kiriting:*";
            sendMessage($cid, $txt, 'markdown');

            file_put_contents("send/step.txt", "send");
            file_put_contents("send/sendt.txt", "us");
            exit;
        } else {
            $txt = "☝️ *Kechirasiz hozir aktiv rassilka mavjud.*

✳️ Umumiy: *" . file_get_contents("send/son.txt") . "*
✅ Yuborildi: *" . file_get_contents("send/bordi.txt") . "*
❎ Yuborilmadi: *$f*

⏰ `$date`";
            sendMessage($cid, $txt, 'markdown');
        }
    }

    if (file_get_contents("send/step.txt") == "send") {
        unlink("send/step.txt");

        if ($message->reply_markup) {
            $repl = json_encode($message->reply_markup);
        } else {
            $repl = $null;
        }

        $ty = file_get_contents('send/sendt.txt');

        if ($ty == "us") {
            $result = mysqli_query($db, "SELECT * FROM Users;");
        }
        $ol = mysqli_num_rows($result);

        if (!file_exists("send/limit.txt")) {
            $a = "100";
        } else {
            $a = file_get_contents("send/limit.txt");
        }

        $txt = "✅ *$ol*ta idga xabar yuborishni boshladim!";
        sendMessage($cid, $txt, 'markdown');

        file_put_contents("send/bordi.txt", "1");
        file_put_contents("send/son.txt", "1");
        file_put_contents("send/umum.txt", "$ol");
        file_put_contents("send/send.txt", "ok");
        file_put_contents("send/message.txt", "$cid=$mid");
        file_put_contents("send/reply.txt", $repl);
    }
}
////////////////////////////////////////////////////////////////////
