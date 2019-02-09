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
    
];

$mysql_credentials = [
'host' => '',
'user' => '',
'password' => '',
'database' => ''
];

use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Commands\SystemCommands\CallbackqueryCommand;

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);
    
    // Custom Commands
    $telegram->addCommandsPaths($commands_paths);
    
    // Mysql
    $telegram->enableMySql($mysql_credentials);
    
    //admin
     $telegram->enableAdmins($admin_users);
     
    //callback
    // CallbackqueryCommand::addCallbackHandler(function (CallbackQuery $query) use ($telegram) {
    //     list($method, $command) = explode(';', $query->getData());
    //     $telegram->{$method}($command);
    // });
     
    // // Logging (Error, Debug and Raw Updates)
    // Longman\TelegramBot\TelegramLog::initErrorLog(__DIR__ . "/{$bot_username}_error.log");
    // Longman\TelegramBot\TelegramLog::initDebugLog(__DIR__ . "/{$bot_username}_debug.log");
    // Longman\TelegramBot\TelegramLog::initUpdateLog(__DIR__ . "/{$bot_username}_update.log");

    // Handle telegram webhook request
    $telegram->handle();
    
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // log telegram errors
    echo $e->getMessage();
}
