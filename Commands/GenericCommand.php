<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Longman\TelegramBot\Commands\SystemCommands;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;
/**
 * Generic command
 *
 * Gets executed for generic commands, when no other appropriate one is found.
 */
class GenericCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'generic';
    /**
     * @var string
     */
    protected $description = 'Merespon ketika command tidak ditemukan';
    /**
     * @var string
     */
    protected $version = '1.1.0';
    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        
        //You can use $command as param
        $chat_id = $message->getChat()->getId();
        $user_id = $message->getFrom()->getId();
        $command = $message->getCommand();
        $nama = $message->getFrom()->getFirstName() .' '. $message->getFrom()->getLastName();
        
        $text = "Hai $nama. Tidak ditemukan perintah /$command. Silahkan ketik atau klik /help untuk melihat daftar perintah.";
        
        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];
        return Request::sendMessage($data);
    }
}
