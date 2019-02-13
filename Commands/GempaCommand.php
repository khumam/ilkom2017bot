<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class GempaCommand extends UserCommand
{
    protected $name = 'gempa';                      // Your command's name
    protected $description = 'Update gempa terkini'; // Your command description
    protected $usage = '/gempa';                    // Usage of your command
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
            
        $data = file_get_contents("http://data.bmkg.go.id/lastgempadirasakan.xml");
	$xml = simplexml_load_string($data);
	$json = json_encode($xml);
	$array = json_decode($json,TRUE);
	$gempa = $array['Gempa'];
	
	$tanggal = $array['Gempa']['Tanggal'];
	$jam = $array['Gempa']['Jam'];
	$lintang = $array['Gempa']['Lintang'];
	$bujur = $array['Gempa']['Bujur'];
	$mag = $array['Gempa']['Magnitude'];
	$kedalaman = $array['Gempa']['Kedalaman'];
	$ket = $gempa['Keterangan'];
	$dirasa = $gempa['Dirasakan'];

	$text = "Gempa Bumi Terkini :\n\n";
	$text .= "Tanggal : $tanggal\nJam : $jam\n";
	$text .= "Lokasi\nLintang : $lintang\nBujur : $bujur\n";
	$text .= "Magnitudo : $mag\nKedalaman : $kedalaman\n$ket\n";
	$text .= "Dirasakan : $dirasa\n";
	
	$text .= "\nSumber BMKG";
	
	$kirimpesan = [
		'chat_id' => $chat_id,
		'parse_mode' => 'HTML',
		'text' => $text
	];
	
	return Request::sendMessage($kirimpesan);

        
    }
}
