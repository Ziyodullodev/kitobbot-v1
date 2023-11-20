<?php


$admin = '848796050';
$token = '5010091658:AAE2JWScgpjmri4BuFX7VW6wSgvTv0nK95I';


$lan = json_encode([
    'inline_keyboard' => [
        [["text" => "🇺🇿O`zbek tili", "callback_data" => "uz"], ["text" => "🇷🇺Русский язык", "callback_data" => "ru"]],
        [["text" => "❌", "callback_data" => "del"]],
    ]
]);

$slan = json_encode([
    'inline_keyboard' => [
        [["text" => "🇺🇿O`zbek tili", "callback_data" => "uz"], ["text" => "🇷🇺Русский язык", "callback_data" => "ru"]],
    ]
]);

$images = array("https://i.pinimg.com/originals/7a/07/5a/7a075ae77e31249f5585f38cbeb77dd0.jpg",
"https://th-thumbnailer.cdn-si-edu.com/sWf0xF1il7OWYO8j-PGqwBvxTAE=/1000x750/filters:no_upscale():focal(2550x1724:2551x1725)/https://tf-cmsv2-smithsonianmag-media.s3.amazonaws.com/filer_public/9a/d7/9ad71c28-a69d-4bc0-b03d-37160317bb32/gettyimages-577674005.jpg",
"https://web-static.wrike.com/cdn-cgi/image/width=900,format=auto/blog/content/uploads/2014/07/iStock-587536358.jpg",
"https://hcommons.org/app/uploads/sites/1001669/2022/10/yin-adapted-2-scaled.jpg",
"https://universitypressplc.com/wp-content/uploads/2022/02/Books-on-a-shelf.jpg",
"https://thedailyguardian.com/wp-content/uploads/2022/08/books_759.jpg");
