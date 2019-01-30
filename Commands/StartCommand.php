<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\DB;
use Longman\TelegramBot\Entities\ReplyKeyboardMarkup;

class StartCommand extends SystemCommand
{
    protected $name = 'start';                      // Your command's name
    protected $description = 'Start Bot'; // Your command description
    protected $usage = '/start';                    // Usage of your command
    protected $version = '1.0.0';                  // Version of your command

    public function execute()
    {
    	$pdo = DB::getPdo();
    
        $message = $this->getMessage();            // Get Message object
        $chat_id = $message->getChat()->getId();   // Get the current Chat ID
        $namapengirim = $message->getFrom()->getFirstName() . ' ' .$message->getFrom()->getLastName(); //fullname
        $from_id = $message->getFrom()->getId();   // id 
        
        $cekakun = $pdo->query("select count(*) from user where teleid='$chat_id'")->fetchColumn();
        
        if($cekakun == 0) {
        	
        	$register = $pdo->query("insert into user (nama, teleid) values ('$namapengirim','$from_id')");
        
	        if($register) {
				
                $text = "\xF0\x9F\x98\x8A <b>Selamat datang</b> ".$namapengirim." di Ilkom Bot 2017 Reborn V2.0.\n\nBanyak fitur yang dihapus dan ditambahkan demi kepentingan bersama. Berhasil autentikasi dan Selamat menggunakan.\n\nKlik /help untuk lebih lanjut.\nKlik /about tentang bot ini.";
				
				$data = [                                  // Set up the new message data
		            'chat_id' => $chat_id,                 // Set Chat ID to send the message to
		            'parse_mode' => 'HTML',
		            'text'    => $text , // Set message to send
		        ];

		        return Request::sendMessage($data);        // Send message!
			}
			
			  if(!$register) {
				
                $text = "\xF0\x9F\x98\x9A <b>Selamat datang</b> ".$namapengirim." di Ilkom Bot 2017 Reborn V2.0.\n\nBanyak fitur yang dihapus dan ditambahkan demi kepentingan bersama.Tapi sayangnya gagal autentikasi nih, coba klik /start lagi.";
				
				$data = [                                  // Set up the new message data
		            'chat_id' => $chat_id,                 // Set Chat ID to send the message to
		            'parse_mode' => 'HTML',
		            'text'    => $text , // Set message to send
		        ];

		        return Request::sendMessage($data);        // Send message!
			}
			
		}
		
		if($cekakun != 0) {
			
            $text = "\xF0\x9F\x98\x8A <b>Selamat datang kembali</b> ".$namapengirim." di Ilkom Bot 2017 Reborn V2.0.\n\nBanyak fitur yang dihapus dan ditambahkan demi kepentingan bersama. Selamat menggunakan.\n\nKlik /help untuk lebih lanjut.\nKlik /about tentang bot ini.";

        $data = [                                  // Set up the new message data
            'chat_id' => $chat_id,                 // Set Chat ID to send the message to
            'parse_mode' => 'HTML',
            'text'    => $text, // Set message to send
        ];

        return Request::sendMessage($data);        // Send message!
			
		}
      
    }
}