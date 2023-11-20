<?php

// require_once "config.php";
require_once "autoload.php";
require_once 'lang/i18n.class.php';
require_once 'db/db.php';
$token = '5010091658:AAE2JWScgpjmri4BuFX7VW6wSgvTv0nK95I';
$admin = "848796050";
$tg = new Telegram(['token' => $token]);
$db = new Database($tg);
// $tg->set_webhook("https://{$config['ip_address']}.ngrok-free.app/hook.php");
$updates = $tg->get_webhookUpdates();

if (!empty($updates)) {
    if (!empty($updates['message']['chat']['id'])) {
        $tg->set_chatId($updates['message']['chat']['id']);
        $user_data = $db->get_user($updates['message']['chat']['id']);
        $language = $user_data['lang'] ?? "uz";
        $i18n = new i18n('lang/lang/lang_{LANGUAGE}.ini', 'langcache/', $language);
        $i18n->init();
        $i18n->getAppliedLang();
        $i18n->getCachePath();
    }
    if (!empty($updates['message']['text'])) {
        require_once 'message.php';
    }
    elseif (!empty($updates['callback_query']['data'])) {
        $tg->set_chatId($updates['callback_query']['message']['chat']['id']);
        $user_data = $db->get_user($updates['callback_query']['message']['chat']['id']);
        $language = $user_data['lang'] ?? "uz";
        $i18n = new i18n('lang/lang/lang_{LANGUAGE}.ini', 'langcache/', $language);
        $i18n->init();
        $i18n->getAppliedLang();
        $i18n->getCachePath();
        require_once 'callback.php';
    }
}
