<?php

namespace Longman\TelegramBot\Commands\AdminCommands;
use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\DB;
use Longman\TelegramBot\Request;

class ReorderCommand extends AdminCommand
{

    protected $name = 'reorder';

    protected $description = 'Reorder ID of info or beasiswa tables';

    protected $usage = '/reorder [info atau beasiswa]';

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
        $kunci = trim($message->getText(true));
        
        
        Request::sendChatAction([
            'chat_id' => $chat_id,
            'action' => 'typing'
            ]);
        
        if($kunci === '' ) {
            
            $text = "Kirim pesan dengan menggunakan perintah di bawah ini\n\n" . $this->getUsage();
            
            $kirimpesan = [
                    'chat_id' => $chat_id,
                    'parse_mode' => 'HTML',
                    'text' => $text
                ];
            
            return Request::sendMessage($kirimpesan);
            
        } else {
            
            
                $query = $pdo->query("SET @count = 0; UPDATE $kunci SET $kunci.id = @count:= @count + 1; ALTER TABLE $kunci AUTO_INCREMENT = 1;");
                
                if($query) {
                    
                    $text = "Berhasil merubah urutan ID.";
                }
                
                if(!$query) {
                    
                    $text = "Gagal merubah urutan ID";
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