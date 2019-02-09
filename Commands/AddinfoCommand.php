<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\DB;

date_default_timezone_set('Asia/Jakarta');

class AddinfoCommand extends UserCommand
{
    protected $name = 'addinfo';                                        // Your command's name
    protected $description = 'Tambah Info';                             // Your command description
    protected $usage = '/addinfo [perihal] | [pesan]';                    // Usage of your command
    protected $version = '1.0.0';                                       // Version of your command

    public function execute()
    {
        $message = $this->getMessage();                 // Get Message object
        $pdo = DB::getPdo();
        $chat_id = $message->getChat()->getId();        // Get the current Chat ID
        $from_id = $message->getFrom()->getId();
        $pesanuser = trim($message->getText(true));
        $nama = $message->getFrom()->getFirstName().' '.$message->getFrom()->getLastName();
        
        Request::sendChatAction([
            'chat_id' => $chat_id,
            'action'  => 'typing',
        ]);
        
        $split = explode("|", $pesanuser);
        $perihal = $split[0];
        $isi = ltrim($split[1]);
        
        $auth = $pdo->query("select is_ban from user where teleid = '$chat_id'")->fetchColumn();
        
        if($auth == 1){
            
            $text = "Maaf anda tidak memiliki akses";
            $kirimpesan = [
                'chat_id' => $chat_id,
                'text' => $text
                ];
            
            return Request::sendMessage($kirimpesan);
        } 
        
        
        if($pesanuser === ''){
            
            $text = "Pesan harus menggunakan perintah\n\n"
            . $this->getUsage()
            . "\n\nGunakan | sebagai pemisah\nPesan bisa menggunakan tag html seperti bold, italic, link, code, dan pre."
            . "\n\n<b>Contoh:</b>\n<code>/addinfo Pindah jadwal | Matematika diskrit pindah jadwal</code>";
            
            $kirimpesan = [
                'chat_id' => $chat_id,
                'parse_mode' => 'HTML',
                'text' => $text
            ];
            
            return Request::sendMessage($kirimpesan);
        }
        
        else {
            
            $waktu = date('d-F-Y , H:i:s');
            $query = $pdo->query("insert into info (dari, judul, pesan, date) values ('$nama','$perihal','$isi', '$waktu')");
            
            if($query) {
                
                $text = "Berhasil menambah info baru. Terima kasih $nama\n\n";
                $text .= "Perihal : $perihal \n\n";
                $text .= "Pesan : $isi";
            }
            
            if(!$query) {
                
                $text = "Gagal menambah info baru. Silahkan coba lagi.";
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