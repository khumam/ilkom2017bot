<?php
// Load composer
require __DIR__ . '/vendor/autoload.php';

$bot_api_key  = '';
$bot_username = '';

$commands_paths = [
    __DIR__ . '/Commands',
    // __DIR__ . '/Commands/AdminCommands',
];

$admin_users = [
    ,
];

$mysql_credentials = [
'host' => 'localhost',
'user' => '',
'password' => '',
'database' => ''
];

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);
    
    // Custom Commands
    $telegram->addCommandsPaths($commands_paths);
    
    // Mysql
    $telegram->enableMySql($mysql_credentials);
    
    //admin
     $telegram->enableAdmins($admin_users);

    // Handle telegram webhook request
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // log telegram errors
    echo $e->getMessage();
}
