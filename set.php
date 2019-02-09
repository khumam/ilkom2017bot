<?php
// Load composer
require __DIR__ . '/vendor/autoload.php';

$bot_api_key  = '660703133:AAGqq_b-jELtlomEi4YEQPDlDI088u0PmNk';
$bot_username = 'ilkom2017bot';
$hook_url     = 'https://ilkomunnes.000webhostapp.com/ilkom2017bot/hook.php';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Set webhook
    $result = $telegram->setWebhook($hook_url);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    // echo $e->getMessage();
}