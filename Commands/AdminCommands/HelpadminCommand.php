<?php

namespace Longman\TelegramBot\Commands\AdminCommands;
use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\DB;
use Longman\TelegramBot\Request;

class HelpadminCommand extends AdminCommand
{

    protected $name = 'helpadmin';

    protected $description = 'Show list of Admin Commands';

    protected $usage = '/helpadmin';

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
        
        Request::sendChatAction([
            'chat_id' => $chat_id,
            'action' => 'typing'
            ]);
        
        $text = "Admin Commands\n\n";
        $text .= "/listmember = Melihat list anggota bot.\n";
        $text .= "/removemember = Menghapus anggota.\n";
        $text .= "/debug = Melihat identitas bot dan server serta debuging webhook.\n";
        $text .= "/sendto = Mengirim pesan ke anggota.\n";
        $text .= "/addjadwal = Menambah jadwal.\n";
        $text .= "/cleaninfo = Menghapus Informasi.\n";
        $text .= "/cleanbeasiswa = Menghapus Informasi Beasiswa.\n";
        $text .= "/reorder = Mengurutkan kembali ID di dalam table.\n";
        $text .= "/helpadmin = Mengakses list command admin.\n";
        
        $kirimpesan = [
                'chat_id' => $chat_id,
                'parse_mode' => 'HTML',
                'text' => $text
            ];
        
        return Request::sendMessage($kirimpesan);
        
    }
}