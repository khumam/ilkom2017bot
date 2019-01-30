<?php

namespace Longman\TelegramBot\Commands\AdminCommands;
use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\DB;
use Longman\TelegramBot\Request;

class CleanbeasiswaCommand extends AdminCommand
{

    protected $name = 'cleanbeasiswa';

    protected $description = 'Clean Scholarship Info Fields';

    protected $usage = '/cleanbeasiswa [id/all]';

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
        $id = trim($message->getText(true));
        
        if($id === '') {
            
            $text = "Gunakan perintah di bawah ini\n\n". $this->getUsage();
        }
        
        elseif($id == 'all') {
            
            $query = $pdo->query("delete from beasiswa");
            
            if($query){
                
                $text = "Berhasil menghapus seluruh info beasiswa.";
            } 
            
            if(!$query) {
                
                $text = "Gagal menghapus seluruh info beasiswa.";
            }
        }
        
        else {
            
            $query = $pdo->query("delete from beasiswa where id = '$id");
            
            if($query){
                
                $text = "Berhasil menghapus info beasiswa dengan ID : $id";
            } 
            
            if(!$query) {
                
                $text = "Gagal menghapus info beasiswa dengan ID : $id , silahkan coba lagi.";
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