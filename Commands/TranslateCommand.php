<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class TranslateCommand extends UserCommand
{
    protected $name = 'translate';                      
    protected $description = 'terjemahan kata atau kalimat'; 
    protected $usage = '/translate atau /tr [kalimat] | [bahasa:optional]';                    
    protected $version = '1.0.0';  

    public function execute()
    {
        $message = $this->getMessage();            

        $chat_id = $message->getChat()->getId();   
        $from_id = $message->getFrom()->getId();
        $nama = $message->getFrom()->getFirstName().' '.$message->getFrom()->getLastName();
        $isi = trim($message->getText(true));
        $split = explode('|', $isi);
        $kalimat = str_replace(' ', '%20',trim($split[0]));
        $bahasa = trim($split[1]);
        $kodebahasa = file_get_contents('https://dyseo.herokuapp.com/listlang');
        $kodebhs = json_decode($kodebahasa, true);
        
        Request::sendChatAction([
            'chat_id' => $chat_id,
            'action'  => 'typing',
        ]);
        
        if($isi === '') {
            
            $text = "Gunakan perintah dibawah ini untuk menggunakan fitur Translate\n\n" . $this->getUsage();
            $text .= "\n\nContoh:\n\n<code>/translate Budidaya ikan lele</code>\natau\n";
            $text .= "<code>/tr Budidaya ikan lele</code>\natau\n";
            $text .= "<code>/tr Budidaya ikan lele | ru</code>\nru adalah kode bahasa untuk rusia\n\n";
            $text .= "Untuk kode bahasa bisa dilihat dengan mengetikkan\n<code>/translate listlang</code>\n<code>/tr listlang</code>\n";
            $text .= "Default : menterjemahkan bahasa ke bahasa Inggris";
            
            $kirimpesan = [
                    'chat_id' => $chat_id,
                    'parse_mode' => 'HTML',
                    'text' => $text
                ];
            return Request::sendMessage($kirimpesan);
        } 
        
        else {
            
            if(!empty($split[1])){
                
                $data = file_get_contents('https://dyseo.herokuapp.com/translate?text='. $kalimat .'&dest='. $bahasa .'&src=auto');
                $decdata = json_decode($data, true);
                
                $hasiltranslate = $decdata['translate-text'];
                $possible = $decdata['data']['possible-translations'][0][2];
                $dibaca = $decdata['data']['translation'][1][2];
                $i = 1;
                
                foreach($kodebhs as $kod => $bhsa) :
                    
                    if($bahasa === $kod) {
                        $dest = $bhsa;
                    }
                endforeach;
                
                
                $text = "Hai $nama, berikut Hasil terjemahan <b>$split[0]</b> dalam Bahasa $dest\n\n";
                $text .= "<i>$hasiltranslate</i>\n";
                
                if($dibaca){
                    $text .= "$dibaca \n";
                }
                
                
                if($possible){
                    $text .= "\nTerjemahan lain yang memungkinkan\n";
                
                foreach ($possible as $lain) :
                    
                    $text .= "$i. " . $lain[0] . "\n";
                    $i++;
                endforeach;
                }
                
            }
            
            else {
                
                if($kalimat === 'listlang') {
                    
                    $data = file_get_contents('https://dyseo.herokuapp.com/listlang');
                    $decdata = json_decode($data, true);
                    
                    $text = "Berikut list bahasa yang dapat digunakan\n\n";
                    
                    foreach($decdata as $lang => $bhs) :
                        
                        $text .= "$bhs = $lang\n";
                    endforeach;
                } 
                
                else {
                
                    $data = file_get_contents('https://dyseo.herokuapp.com/translate?text='. $kalimat .'&dest=en&src=auto');
                    $decdata = json_decode($data, true);
                    
                    $hasiltranslate = $decdata['translate-text'];
                    
                    $text = "Hai $nama, berikut Hasil terjemahan <b>$split[0]</b>\n\n";
                    $text .= "<i>$hasiltranslate</i> \n";
                    
                    if($dibaca){
                        $text .= "$dibaca \n";
                    }
                    
                    if($possible){
                        $text .= "\nTerjemahan lain yang memungkinkan\n";
                    
                        foreach ($possible as $lain) :
                            
                            $text .= "$i. " . $lain[0] . "\n";
                            $i++;
                        endforeach;
                    }
                
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