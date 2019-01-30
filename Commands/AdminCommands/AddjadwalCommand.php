<?php

namespace Longman\TelegramBot\Commands\AdminCommands;
use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\DB;
use Longman\TelegramBot\Request;

class AddjadwalCommand extends AdminCommand
{

    protected $name = 'addjadwal';

    protected $description = 'Menambahkan jadwal baru';

    protected $usage = '/addjadwal [mata pelajaran] | [hari] | [jam] | [tempat] | [rombel: 1/2]';

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
        $datapesan = trim($message->getText(true));
        $decdata = explode('|', $datapesan);
        $makul = ucfirst($decdata[0]);
        $hari = ucfirst($decdata[1]);
        $jam = ucfirst($decdata[2]);
        $tempat = ucfirst($decdata[3]);
        $rombel = ucfirst($decdata[4]);
        
        Request::sendChatAction([
            
            'chat_id' => $chat_id,
            'action' => 'typing'
            ]);
        
        if($datapesan === ''){
            
            $text = "Semua data harus diisi. Pastikan sesuai dengan perintah di bawah ini\n\n" . $this->getUsage();
            $text .= "<b>\n\nContoh :\n\n/addjadwal Etika Profesi | Senin | 07.00 | D2-310 | 2</b>";
            
            $kirimpesan = [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => $text
            ];
            
            return Request::sendMessage($kirimpesan);
        }
        
        elseif(empty($makul) || empty($hari) || empty($jam) || empty($tempat) || empty($rombel)) {
            
            $text = "Semua data harus diisi. Pastikan sesuai dengan perintah di bawah ini\n\n" . $this->getUsage();
            $text .= "<b>\n\nContoh :\n\n/addjadwal Etika Profesi | Senin | 07.00 | D2-310 | 2</b>";
            
            $kirimpesan = [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => $text
            ];
            
            return Request::sendMessage($kirimpesan);
        }
        
        else {
            
            $query = $pdo->query("insert into jadwal values(makul, hari, jam, tempat, rombel) values ('$makul','$hari','$jam','$tempat','$rombel')");
            
            if(!$query) {
                
                $text = "Gagal menambahkan data jadwal";
                
                $kirimpesan = [
                'chat_id' => $chat_id,
                'parse_mode' => 'HTML',
                'text' => $text
                ];
                
                return Request::sendMessage($kirimpesan);
            }
            
            if($query) {
                
                $text = "Berhasil menambahkan data\n\n";
                $text .= "Mata Kuliah : " . $makul;
                $text .= "\nHari : " . $hari;
                $text .= "\nJam : " . $jam;
                $text .= "\nTempat : " . $tempat;
                $text .= "\nRombel : " . $rombel;
                
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