<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class PyCommand extends UserCommand
{
    protected $name = 'py';                      // Your command's name
    protected $description = 'Run Python Script'; // Your command description
    protected $usage = '/py [code]';                    // Usage of your command
    protected $version = '1.0.0';                  // Version of your command

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object

        $chat_id = $message->getChat()->getId();   // Get the current Chat ID
        $from_id = $message->getFrom()->getId();
        $nama = $message->getFrom()->getFirstName().' '.$message->getFrom()->getLastName();
	$code = trim($message->getText(true));
	$code1 = "$code";
	$program = urlencode($code);
        
        Request::sendChatAction([
                'chat_id' => $chat_id,
                'action' => 'typing'
            ]);

	if($code === ''){
	$text = "Gunakan perintah di bawah ini\n/py [code]";
	$text .= "\n\nContoh :<code>/py print(&#34;hello world&#34;) </code>";
	
	$kirimpesan = [
		'chat_id' => $chat_id,
		'parse_mode' => 'HTML',
		'text' => $text
	];
	
	return Request::sendMessage($kirimpesan);
	
	}

	$data = file_get_contents("http://rextester.com/rundotnet/api?LanguageChoice=24&Program=" . $program);
	$decdata = json_decode($data, true);
	$warning = $decdata['Warnings'];
	$errors = $decdata['Errors'];
	$res	= $decdata['Result'];
	$st	= $decdata['Stats'];

	$text = $res ."\n\n";
	$text .= "<code>".$st ."</code>\n\n";
	if($warning != null){
	$text .= "Warning : <code>".$warning ."</code>\n";
	}
	if($errors != null){
	$text .= "Errors : <code>".$errors ."</code>\n";
	}

	return Request::sendMessage([
		'chat_id' => $chat_id,
		'parse_mode' => 'HTML',
		'text' => $text
	]);

        
    }
}
