<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class JadwalCommand extends UserCommand
{
    protected $name = 'jadwal';                      
    protected $description = 'Informasi Jadwal';
    protected $usage = '/jadwal [hari:optional] | [rombel:optional]';                   
    protected $version = '1.0.0';                  

    public function execute()
    {
        $message = $this->getMessage();    
        
        Request::sendChatAction([
                'chat_id' => $chat_id,
                'action' => 'typing'
        ]);

        $chat_id = $message->getChat()->getId();   
        $from_id = $message->getFrom()->getId();
        $nama = $message->getFrom()->getFirstName() .' '. $message->getFrom()->getLastName();
        $nilai = trim($message->getText(true));
        $split = explode('|', $nilai);
        $hari = trim($split[0]);
        $rombel = trim($split[1]);
        
        // $auth = $pdo->query("select is_ban from user where teleid = '$chat_id'")->fetchColumn();
        
        // if($auth == 1){
            
        //     $text = "Maaf anda tidak memiliki akses";
        //     $kirimpesan = [
        //         'chat_id' => $chat_id,
        //         'text' => $text
        //         ];
            
        //     return Request::sendMessage($kirimpesan);
        // } 
        
       Request::sendChatAction([
           'chat_id' => $chat_id,
           'action' => 'typing'
           ]);
        
        if($nilai === ''){
            
            $data = file_get_contents('http://ilkomunnes.000webhostapp.com/api/jadwal/{APIKEY}');
            $decdata = json_decode($data, true);
            
            $status = $decdata['status'];
            $listjadwal1 = $decdata['data'][0]['rombel 1'];
	    $listjadwal2 = $decdata['data'][1]['rombel 2'];
		$i = 1;
		$j = 1;
            
            if($status == 'NotFound') {
                
                $text = "Hai $nama. Jadwal belum tersedia untuk saat ini";
            }
            
            else {
                
                $text = "Hai $nama. Jadwal hari ini\n\n";
     		$text = "<b>Rombel 1</b>\n\n";
                
                foreach($listjadwal1 as $ls1) :
                    
                    $text .= "<b>$i. " . $ls1['makul'] . "</b>\n";
                    $text .= "<b>Rombel : " . $ls1['rombel'] . "</b>\n";
                    $text .= $ls1['hari'] . ", jam " . $ls1['jam'] . " WIB\n";
                    $text .= "Tempat : " . $ls1['tempat'] . "\n";
			$i++;
                endforeach;

                $text = "\n<b>Rombel 2</b>\n\n";

		foreach($listjadwal2 as $ls2) :
                    
                    $text .= "<b>$j. " . $ls2['makul'] . "</b>\n";
                    $text .= "<b>Rombel : " . $ls2['rombel'] . "</b>\n";
                    $text .= $ls2['hari'] . ", jam " . $ls2['jam'] . " WIB\n";
                    $text .= "Tempat : " . $ls2['tempat'] . "\n\n";
		    $j++;
                endforeach;
                
                $help = "\n\nGunakan perintah di bawah ini\n\n";
                $help .= "/jadwal = Melihat jadwal saat itu\n";
                $help .= "/jadwal [hari] = Melihat jadwal berdasarkan hari\n";
                $help .= "/jadwal [rombel] = Melihat jadwal berdasarkan rombel\n";
                $help .= "/jadwal [hari] | [rombel] = Melihat jadwal berdasarkan hari dan rombel";
            }
            
            $kirimpesan = [
                'chat_id' => $chat_id,
                'parse_mode' => 'HTML',
                'text' => $text
            ];
        
            Request::sendMessage($kirimpesan);
            
            $kirimhelp= [
                'chat_id' => $chat_id,
                'parse_mode' => 'HTML',
                'text' => $help
            ];
        
            return Request::sendMessage($kirimhelp);
        }
        
        elseif($nilai === 'all'){
            
            $data = file_get_contents('http://ilkomunnes.000webhostapp.com/api/jadwal/all/{APIKEY}');
            $decdata = json_decode($data, true);
            
            $status = $decdata['status'];
            $listjadwal1 = $decdata['data'][0]['rombel 1'];
	    $listjadwal2 = $decdata['data'][1]['rombel 2'];
		$i = 1;
		$j = 1;
            
            if($status == 'NotFound') {
                
                $text = "Hai $nama. List semua jadwal belum tersedia untuk saat ini";
            }
            
            else {
                
                $text = "Hai $nama. Jadwal berikut seluruh rombel\n\n";
     		$text = "<b>Rombel 1</b>\n\n";
		
                foreach($listjadwal1 as $ls1) :
                    
                    $text .= "<b>$i. " . $ls1['makul'] . "</b>\n";
                    $text .= "<b>Rombel : " . $ls1['rombel'] . "</b>\n";
                    $text .= $ls1['hari'] . ", jam " . $ls1['jam'] . " WIB\n";
                    $text .= "Tempat : " . $ls1['tempat'] . "\n";
			$i++;
                endforeach;

                $text = "\n<b>Rombel 2</b>\n\n";

		foreach($listjadwal2 as $ls2) :
                    
                    $text .= "<b>$j. " . $ls2['makul'] . "</b>\n";
                    $text .= "<b>Rombel : " . $ls2['rombel'] . "</b>\n";
                    $text .= $ls2['hari'] . ", jam " . $ls2['jam'] . " WIB\n";
                    $text .= "Tempat : " . $ls2['tempat'] . "\n";
			$j++;
                endforeach;
            }
            
            $kirimpesan = [
                'chat_id' => $chat_id,
                'parse_mode' => 'HTML',
                'text' => $text
            ];
        
            return Request::sendMessage($kirimpesan);
            
        } 
        
        else {
            
            if($rombel){
                
                $data = file_get_contents('http://ilkomunnes.000webhostapp.com/api/jadwal/hari/'. $hari .'/rombel/'. $rombel .'/{APIKEY}');
                $decdata = json_decode($data, true); 
                
                $status = $decdata['status'];
                $listjadwal = $decdata['data'];
		$i=1;
                
                if($status == 'NotFound') {
                    
                    $text = "Hai $nama. Jadwal pada hari ". ucfirst($hari) ." rombel $rombel belum tersedia untuk saat ini";
                }
                
                else {
                    
                    $text = "Hai $nama. Berikut jadwal untuk hari ". ucfirst($hari) ." rombel $rombel";
                    
                    foreach($listjadwal as $ls) :
                    
                    $text .= "<b>$i. " . $ls['makul'] . "</b>\n";
                    $text .= "<b>Rombel : " . $ls['rombel'] . "</b>\n";
                    $text .= $ls['hari'] . ", jam " . $ls['jam'] . " WIB\n";
                    $text .= "Tempat : " . $ls['tempat'] . "\n\n"; 
			$i++;
                    endforeach;
                    
                }
                
                $kirimpesan = [
                'chat_id' => $chat_id,
                'parse_mode' => 'HTML',
                'text' => $text
                ];
        
                return Request::sendMessage($kirimpesan);
            
            }
            
            else {
                
                if($hari == '1' || $hari == '2'){
                    
                    $data = file_get_contents('http://ilkomunnes.000webhostapp.com/api/jadwal/rombel/'. $hari .'/{APIKEY}');
                    $decdata = json_decode($data, true); 
                    
                    $status = $decdata['status'];
                    $listjadwal = $decdata['data'];
			$i = 1;
                    
                    if($status == 'NotFound') {
                        
                        $text = "Hai $nama. Jadwal untuk rombel $hari belum tersedia untuk saat ini";
                    }
                    
                    else {
                        
                        $text = "Hai $nama. Berikut jadwal untuk rombel $hari\n\n";
                        
                        foreach($listjadwal as $ls) :
                    
                        $text .= "<b>$i. " . $ls['makul'] . "</b>\n";
                        $text .= "<b>Rombel : " . $ls['rombel'] . "</b>\n";
                        $text .= $ls['hari'] . ", jam " . $ls['jam'] . " WIB\n";
                        $text .= "Tempat : " . $ls['tempat'] . "\n\n";
			$i++;
                        endforeach;
                    }
                }
                
                else {
                    
                    $data = file_get_contents('http://ilkomunnes.000webhostapp.com/api/jadwal/hari/'. $hari .'/{APIKEY}');
                    $decdata = json_decode($data, true); 
                    
                    $status = $decdata['status'];
                    $listjadwal1 = $decdata['data'][0]['rombel 1'];
	    		$listjadwal2 = $decdata['data'][1]['rombel 2'];
			$i = 1;
			$j = 1;
                    
                    if($status == 'NotFound') {
                        
                        $text = "Hai $nama. Jadwal pada hari $hari belum tersedia untuk saat ini";
                    }
                    
                    else {
                        
                        $text = "Hai $nama. Berikut Jadwal pada hari $hari\n\n";
                        
                        $text = "<b>Rombel 1</b>\n\n";
		
                foreach($listjadwal1 as $ls1) :
                    
                    $text .= "<b>$i. " . $ls1['makul'] . "</b>\n";
                    $text .= "<b>Rombel : " . $ls1['rombel'] . "</b>\n";
                    $text .= $ls1['hari'] . ", jam " . $ls1['jam'] . " WIB\n";
                    $text .= "Tempat : " . $ls1['tempat'] . "\n";
			$i++;
                endforeach;

                $text = "\n<b>Rombel 2</b>\n\n";

		foreach($listjadwal2 as $ls2) :
                    
                    $text .= "<b>$j. " . $ls2['makul'] . "</b>\n";
                    $text .= "<b>Rombel : " . $ls2['rombel'] . "</b>\n";
                    $text .= $ls2['hari'] . ", jam " . $ls2['jam'] . " WIB\n";
                    $text .= "Tempat : " . $ls2['tempat'] . "\n";
			$j++;
                endforeach;
                    }
                }
                
                $kirimpesan = [
                'chat_id' => $chat_id,
                'parse_mode' => 'HTML',
                'text' => $text
                ];
        
                 return Request::sendMessage($kirimpesan);
            }
        }
        
      
    }
}
