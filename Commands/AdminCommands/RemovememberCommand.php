<?php

namespace Longman\TelegramBot\Commands\AdminCommands;
use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\DB;
use Longman\TelegramBot\Request;

class RemovememberCommand extends AdminCommand
{

    protected $name = 'removemember';

    protected $description = 'Remove Member';

    protected $usage = '/removemember [id]';

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
        
        else {
            
            $query = $pdo->query("delete from user where teleid = $id");
            
            if($query){
                
                $text = "Berhasil menghapus user dengan ID : $id";
            } 
            
            if(!$query) {
                
                $text = "Gagal menghapus user, periksa kembali ID yang akan di hapus";
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