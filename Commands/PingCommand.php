<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class PingCommand extends UserCommand
{
    protected $name = 'ping';                      // Your command's name
    protected $description = 'ping'; // Your command description
    protected $usage = '/ping';                    // Usage of your command
    protected $version = '1.0.0';                  // Version of your command

    public function execute() {
        
        $message = $this->getMessage();            // Get Message object

        $chat_id = $message->getChat()->getId();   // Get the current Chat ID
        $nama = $message->getFrom()->getFirstName() .' '. $message->getFrom()->getLastName();
	$host = trim($message->getText(true));

	if($host === '') {
		$host = "ilkom2017bot.herokuapp.com";
	}
        
        $start = microtime(true);
    	$fp = fsockopen($host, 80, $errorCode, $errorCode, 5);
    	$end = microtime(true);
    		if($fp === false){
        		$hasil = "Koneksi gagal";
    		}
    	fclose($fp);
    	$diff = $end - $start;
	$hasil = $diff * 1000 . " ms";
	
	$text = "Hasil: $hasil";
	
        Request::sendChatAction([
            
            'chat_id'=> $chat_id,
            'action' => 'typing'
            ]);
        
        $kirimpesan = [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => $text
            ];
        
        return Request::sendMessage($kirimpesan);
    }
}
