<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class PesanCommand extends UserCommand
{
    protected $name = 'pesan';                      
    protected $description = 'Kirim kritik, saran, pesan, atau request fitur'; 
    protected $usage = '/pesan [text]';                    
    protected $version = '1.0.0';                  

    public function execute()
    {
        $message = $this->getMessage();            

        $chat_id = $message->getChat()->getId();   
        $from_id = $message->getFrom()->getId();
        $nama = $message->getFrom()->getFirstName().' '.$message->getFrom()->getLastName();
        $isi = trim($message->getText(true));
        
        if($isi === '') {
            
            $text = "Gunakan perintah dibawah ini untuk mengirim pesan ke mimin\n\n" . $this->getUsage();
            $text .= "\n\n<b>Contoh:</b>\n<code>/pesan Hai Min!</code>";
            
            $kirimpesan = [
                    'chat_id' => $chat_id,
                    'parse_mode' => 'HTML',
                    'text' => $text
                ];
            return Request::sendMessage($kirimpesan);
        } 
        
        else {
            
            $kirimkemimin = [
                'chat_id'=> '488866943',
                'text' => "Pesan baru dari $nama.\n\n$isi"
                ];
            
            $cek = Request::sendMessage($kirimkemimin);
            
            if($cek){
                
                $text = "Berhasil mengirim pesan. Terima kasih $nama.";
                $kirimpesan = [
                    'chat_id' => $chat_id,
                    'parse_mode' => 'HTML',
                    'text' => $text
                ];
                return Request::sendMessage($kirimpesan);
            }
            
            if(!$cek) {
                
                $text = "Wah ada gangguan mengirim pesan. Coba lagi";
                
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