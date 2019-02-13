<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class CppCommand extends UserCommand
{
    protected $name = 'cpp';                      // Your command's name
    protected $description = 'Run C++ Script'; // Your command description
    protected $usage = '/cpp [code]';                    // Usage of your command
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
	$text = "Gunakan perintah di bawah ini\n/cpp [code]";
	$text .= "\n\nContoh :\n<code>/cpp &#10;#include &lt;iostream&gt;&#10;using namespace std;&#10;&#10;int main(){&#10;cout &lt;&lt; &#34;Hello World&#34;;&#10;} </code>";
	
	$kirimpesan = [
		'chat_id' => $chat_id,
		'parse_mode' => 'HTML',
		'text' => $text
	];
	
	return Request::sendMessage($kirimpesan);
	
	}

	$data = file_get_contents("http://rextester.com/rundotnet/api?LanguageChoice=7&Program=" . $program ."&CompilerArgs=-Wall%20-std%3Dc%2B%2B14%20-O2%20-o%20a.out%20source_file.cpp");
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
