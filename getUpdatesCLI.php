#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

$bot_api_key  = '488702832:AAGgIeVJseZjY9iiGUoVLuMkNAkvI2BVSgo';
$bot_username = 'baymax2bot';

$mysql_credentials = [
   'host'     => 'localhost',
   'port'     => 3306, // optional
   'user'     => 'dbuser',
   'password' => 'dbpass',
   'database' => 'dbname',
];

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Enable MySQL
   // $telegram->enableMySql($mysql_credentials);
$telegram->useGetUpdatesWithoutDatabase();
    // Handle telegram getUpdates request
    $telegram->handleGetUpdates();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    // echo $e->getMessage();
}
