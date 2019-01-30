<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class AboutCommand extends UserCommand
{
    protected $name = 'about';                      // Your command's name
    protected $description = 'Tentang Bot'; // Your command description
    protected $usage = '/about';                    // Usage of your command
    protected $version = '1.0.0';                  // Version of your command

    public function execute() {
        
        $message = $this->getMessage();            // Get Message object

        $chat_id = $message->getChat()->getId();   // Get the current Chat ID
        $nama = $message->getFrom()->getFirstName() .' '. $message->getFrom()->getLastName();
        
        $text = "<b>Hai $nama</b>. Terima kasih sudah menggunakan bot ini. Ini merupakan pembaharuan dari bot sebelumnya. Bot sebelumnya sudah dihapus karena terdapat bug mengirim pesan berulang. Jadi lebih baik dibuat ulang bot baru dengan fitur baru.\n\nSeluruh fitur dari bot sebelumnya sudah dihapus. Dan ini merupakan pembaharuan dari Bot baru Ilkom 2017.\n\n";
        $text .= "<b>1. Update Library</b>\nBot ini menggunakan library atau kode yang berbeda dari sebelumnya. Lebih ringkas, dan praktis.\n\n";
        $text .= "<b>2. Fitur jadwal tetap</b>\nFitur jadwal masih dipasang, bisa untuk melihat jadwal harian, atau per rombel. Bisa diupdate melalui perintah langsung di telegram. Tapi terbatas, hanya beberapa saja yang bisa akses menu admin untuk penambahan jadwal, penghapusan jadwal, dan pengeditan jadwal. Yang mau dapet akses, bilang aja.\n\nRencananya akan ditambahkan fitur reminder jadwal, jadi setiap pagi akan ada notifikasi jadwal di hari itu.\n\n";
        $text .= "<b>3. Fitur Informasi</b>\nAda dua fitur informasi, yaitu informasi umum dan informasi tentang beasiswa. Semua orang bisa mengakses dan menambahkan informasi baru. Gunakan dengan bijak.\n\n";
        $text .= "<b>4. Broadcast [COMING SOON]</b>\nEntah bisa atau enggak, nantinya bisa mengirim pesan broadcast ke seluruh anggota. Masih mempertimbangkan kebijakan, takutnya spam\n\n";
        $text .= "<b>5. Menggunakan API sendiri</b>\nSekarang menggunakan API sendiri untuk mendapatkan data seperti jadwal dan informasi. Jadi gak manual lagi. PUBLIC API akan di share dikemudian hari yang nantinya bisa digunakan apabila ingin membuat aplikasi informasi serupa seperti aplikasi android, web, dan sebagainya\n\n";
        $text .= "Untuk sekarang itu dulu, selengkapnya akan ditambah lagi. Terima kasih everyone \xF0\x9F\x98\x98";

        
        Request::sendChatAction([
            
            'chat_id'=> $chat_id,
            'action' => 'typing'
            ]);
        
        $kirimpesan = [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => $text
            ];
        
        return Request::sendMessage($kirimpesan);
    }
}