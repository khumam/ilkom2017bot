<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class RunCommand extends UserCommand
{
    protected $name = 'run';                      // Your command's name
    protected $description = 'List bahasa yang bisa dijalankan'; // Your command description
    protected $usage = '/run';                    // Usage of your command
    protected $version = '1.0.0';                  // Version of your command

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object

        $chat_id = $message->getChat()->getId();   // Get the current Chat ID
        $from_id = $message->getFrom()->getId();
        $nama = $message->getFrom()->getFirstName().' '.$message->getFrom()->getLastName();
        
        Request::sendChatAction([
                'chat_id' => $chat_id,
                'action' => 'typing'
            ]);

	$text = "Berikut ini list bahasa pemrograman yang bisa dijalankan di bot ini. Pastikan baca cara penggunaanya dengan mengklik perintahnya.\n\n";
	$text .= "/py = Menjalankan script bahasa Python (Python 3).\n";
	$text .= "/php = Menjalankan script bahasa PHP (PHP 7.0.8).\n";
	$text .= "/cpp = Menjalankan script bahasa C++ (g++ 5.4.0).\n";
	$text .= "/js = Menjalankan script bahasa JavaScript (JavaScript-C24.2.0).\n";
	$text .= "/go = Menjalankan script bahasa Go (Go 1.6.2).\n\n";
	$text .= "<b>Note :</b>\n- Perintah yang terlalu kompleks mungkin akan lama dalam mengirim balasannya.\n";
	$text .= "- Tidak semua perintah dapat dijalankan karena ini bukan IDE yang kompleks. Gunakan untuk pembelajaran bukan untuk program yang terlalu kompleks dan memiliki banyak fungsi";

	return Request::sendMessage([
		'chat_id' => $chat_id,
		'parse_mode' => 'HTML',
		'text' => $text
	]);

        
    }
}
