<?php
$admin = '848796050';
$token = '5257832609:AAG7NzV0YDxEXF_HYP8SDidThfbfssS1iKM';


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
        var_dump(curl_error($ch));
    } else {
        return json_decode($res);
    }
}


$update = json_decode(file_get_contents('php://input'));

if ($update) {
    $message = $update->message;
    $mid = $message->message_id;
    $text = $message->text;
    $cid = $message->chat->id;
    $channel = $update->channel_post;
    $channel_mid = $channel->message_id;
}

require "base.php";


if ($channel->document) {
    $doc = $channel->document;
    $name1 = $doc->file_name;
    $type = $doc->mime_type;
    $size = $doc->file_size;
    $link = $doc->file_id;

    $name1 = str_replace("_", " ", $name1);
    $name2 = str_replace("-", " ", $name1);
    if (stristr($name2, "@")) {
        $get = explode("@", $name2);
        $get2 = explode(" ", $get[1]);
        $name1 = str_replace("$get2[0]", "", $name2);
    }
    $name = str_replace("'", "", $name1);
    $name = str_replace("‘", "", $name);

    $get = Kitobol($name, false);
    if ($get) {
        // bot('sendmessage',[
        //     'chat_id'=>848796050,
        //         'text'=>"Bu avval bor edi name: $name",
        //         // 'parse_mode' =>'html'
        //     ]);
        //     mysqli_query($db, "UPDATE Kitoblar SET `Name`='$name1' WHERE `ID`='$get'");
        // mysqli_query($db, "UPDATE Kitoblar SET `Size`='$size', `Type`='$type'  WHERE `ID`='$get[ID]'"); 
        exit();
    }
    mysqli_query($db, "INSERT INTO Books (`Name`,`FileID`,`Size`,`Type`,`Link`) VALUES ('$name','https://t.me/ziyodullokitob/$channel_mid','$size','$type','$link')");
    // bot('sendmessage',[
    //     'chat_id'=>848796050,
    //         'text'=>"Bu avval yoq edi name: $name1, ,, $get[ID]",
    //         // 'parse_mode' =>'html'
    //     ]);
}
if ($channel->audio) {
    $audio = $channel->audio;
    $name1 = $audio->file_name;
    $type = $audio->mime_type;
    $size = $audio->file_size;
    $link = $audio->file_id;

    // $name = explode(".",$name1);
    // $caption1 = str_replace("@", "#", $cap);
    // $cap = str_replace("'", "`", $caption1);
    // $title1 = str_replace("'", "", $title);
    $name1 = str_replace("_", " ", $name1);
    $name2 = str_replace("-", " ", $name1);
    if (stristr($name2, "@")) {
        $get = explode("@", $name2);
        $get2 = explode(" ", $get[1]);
        $name1 = str_replace("$get2[0]", "", $name2);
    }
    $name = str_replace("'", "", $name1);
    $name = str_replace("‘", "", $name);

    $get = Kitobol($name, false);
    if ($get) {
        // bot('sendmessage',[
        //     'chat_id'=>848796050,
        //         'text'=>"Bu avval bor edi name: $name",
        //         // 'parse_mode' =>'html'
        //     ]);
        // mysqli_query($db, "UPDATE Kitoblar SET `Size`='$size', `Type`='$type'  WHERE `ID`='$get[ID]'");
        exit();
    }
    mysqli_query($db, "INSERT INTO Books (`Name`,`FileID`,`Size`,`Type`,`Link`) VALUES ('$name','https://t.me/ziyodullokitob/$channel_mid','$size','$type','$link')");
    // mysqli_query($db, "INSERT INTO Books (`Name`,`Caption`,`FileID`,`Size`,`Type`,`Link`) VALUES ('$name','{$cap}','https://t.me/ziyodullokitob/$channel_mid','$size','$type','$link')");

    // mysqli_query($db, "INSERT INTO `Books` (`Name`,`Caption`,`FileID`,`Size`,`Type`) VALUES ('$name','$cap','https://t.me/Kitoblar_saqla/$channel_mid','$size','$type')");

}

if ($text == "/start") {
    bot('sendmessage', [
        'chat_id' => $cid,
        'text' => "Bu bot ziyodullokitobga kitoblar saqlash kerak!!!",
        'parse_mode' => 'html'
    ]);

    
} elseif ($text == "/jami") {
    $sqlt = mysqli_query($db, "SELECT `ID` FROM `Books`");
    $tek = mysqli_num_rows($sqlt);
    bot('sendmessage', [
        'chat_id' => $cid,
        'text' => "Bazada jami $tek ta kitob bor",
    ]);
} elseif ($text == "/getproduct") {
    $data = file_get_contents("https://onlinezakaz.pythonanywhere.com/product/all/");
    $data = json_decode($data);
    $data = $data[0];
    $id = $data->id;
    $name = $data->name;
    $price = $data->price;
    $boxsize = $data->boxsize;
    $image = $data->image;
    $category = $data->category;
    bot('sendphoto', [
        'chat_id' => $cid,
        'photo'=>"https://onlinezakaz.pythonanywhere.com/$image",
        'caption' => "Maxsulot idsi: $id\nNomi: $name\nNarxi: $price\nKarobkada nechta bor: $boxsize\nCategorya idsi: $category",
    ]);
}
function Kitobol($name)
{
    global $db;
    $sqlt = mysqli_query($db, "SELECT * FROM `Books` WHERE `Name`='$name'");
    $tek = mysqli_fetch_assoc($sqlt);
    return $tek;
}
