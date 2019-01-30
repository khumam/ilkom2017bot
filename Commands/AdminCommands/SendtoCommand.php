<?php

namespace Longman\TelegramBot\Commands\AdminCommands;
use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\DB;
use Longman\TelegramBot\Request;

class SendtoCommand extends AdminCommand
{

    protected $name = 'sendto';

    protected $description = 'Send Message to (All) or specify member';

    protected $usage = '/sendto [id/all] | [teks]';

    protected $version = '1.1.0';
    /**
     * Command execute method
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute() {
        
        $pdo = DB::getPdo();
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $pesan = trim($message->getText(true));
        $split = explode('|', $pesan);
        $byid = str_replace(' ','',strtolower($split[0]));
        $isi = $split[1];
        
        
        Request::sendChatAction([
            'chat_id' => $chat_id,
            'action' => 'typing'
            ]);
        
        if(empty($byid) || empty($isi) ) {
            
            $text = "Kirim pesan dengan menggunakan perintah di bawah ini\n\n" . $this->getUsage();
            
            $kirimpesan = [
                    'chat_id' => $chat_id,
                    'parse_mode' => 'HTML',
                    'text' => $text
                ];
            
            return Request::sendMessage($kirimpesan);
        } else {
            
            if($byid === 'all') {
                
                $query = $pdo->query("select teleid from user order by id desc");
                $user = $query->fetchAll();
                $i = 0;
                foreach ($user as $usr) :
            
                   $cek = Request::sendMessage(['chat_id' => $usr['teleid'], 'parse_mode' => 'HTML', 'text' => $isi]);
                   if($cek) {
                       
                       $i += 1;
                   }
                   
                   if(!$cek) {
                       
                       $i += 0;
                   }
                   usleep(200000);
               
                endforeach;
            
                $text = "Berhasil di kirim ke $i pengguna.";
            
                $kirimpesan = [
                    'chat_id' => $chat_id,
                    'parse_mode' => 'HTML',
                    'text' => $text
                ];
                
                return Request::sendMessage($kirimpesan);
            
            }
            
            else {
                
                $cek1 = Request::sendMessage(['chat_id' => $byid, 'parse_mode' => 'HTML', 'text' => $isi]);
                
                if($cek1) {
                    
                    $text = "Berhasil mengirim pesan ke $byid";
                } 
                
                if(!$cek1) {
                    
                    $text = "Gagal mengirim pesan ke $byid";
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