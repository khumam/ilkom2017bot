<?php

namespace Longman\TelegramBot\Commands\AdminCommands;
use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\DB;
use Longman\TelegramBot\Request;

class ListmemberCommand extends AdminCommand
{

    protected $name = 'listmember';

    protected $description = 'List All Member who start the Bot';

    protected $usage = '/listmember';

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
        
        $query = $pdo->query("select * from user order by id desc");
        $user = $query->fetchAll();
        $i = 1;
        $text = "<b>List member BOT</b>\n\n";
        
        if($query) {
        
        foreach ($user as $usr) :
            
            $text .= "<b>$i. " . $usr['nama'] . "</b>\n";
            $text .= "ID : " . $usr['teleid'] . "\n\n";
            $i++;
        endforeach;
        
        $kirimpesan = [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => $text
            ];
            
        return Request::sendMessage($kirimpesan);
        
        }
        
        if(!$query){
            
            $text = "Gagal mengambil data.";
            
            $kirimpesan = [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => $text
            ];
            
        return Request::sendMessage($kirimpesan);
        }
    }
}