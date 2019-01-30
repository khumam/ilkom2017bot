<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class KbbiCommand extends UserCommand
{
    protected $name = 'kbbi';                      
    protected $description = 'Kamus Besar Bahasa Indonesia'; 
    protected $usage = '/kbbi [kata]';                    
    protected $version = '1.0.0';                  

    public function execute()
    {
        $message = $this->getMessage();            

        $chat_id = $message->getChat()->getId();   
        $from_id = $message->getFrom()->getId();
        $nama = $message->getFrom()->getFirstName().' '.$message->getFrom()->getLastName();
        $isi = trim($message->getText(true));
        
        if($isi === '') {
            
            $text = "Gunakan perintah dibawah ini untuk menggunakan fitur KBBI\n\n" . $this->getUsage();
            $text .= "\n\n<b>Contoh:</b>\n<code>/kbbi Cinta</code>";
            
            $kirimpesan = [
                    'chat_id' => $chat_id,
                    'parse_mode' => 'HTML',
                    'text' => $text
                ];
            return Request::sendMessage($kirimpesan);
        } 
        
        else {
            
            $getdata = file_get_contents('http://kateglo.com/api.php?format=json&phrase='.$isi);
            $kbbi = json_decode($getdata, true);
            
            $kelas = ucfirst($kbbi['kateglo']['lex_class_ref']);
            $definisi = $kbbi['kateglo']['definition'];
            
            if(!$kbbi) {
                
                $text = "Maaf $nama. Nampaknya kata yang dicari tidak ditemukan. Coba lagi ya.";
                
            } else {
                
                $text = "Hasil dari KBBI untuk kata " . ucfirst($isi) . ' (' . $kelas . ")\n\n";
                $i = 1;
                
                foreach ($definisi as $def) :
                    
                    $text .= "<b>$i. " . $def['def_text'] . "</b>\n";
                    
                    if(empty($def['sample'])){
                        $text .= "\n";
                    }
                    
                    if(!empty($def['sample'])) {
                        $text .= "<i>Contoh : " . $def['sample'] . "</i>\n\n";
                    }
                    
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

    }
}