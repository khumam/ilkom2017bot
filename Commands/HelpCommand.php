<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class HelpCommand extends UserCommand
{
    protected $name = 'help';                      // Your command's name
    protected $description = 'List Perintah'; // Your command description
    protected $usage = '/help';                    // Usage of your command
    protected $version = '1.0.0';                  // Version of your command

    public function execute() {
        
        $message = $this->getMessage();            // Get Message object

        $chat_id = $message->getChat()->getId();   // Get the current Chat ID
        $nama = $message->getFrom()->getFirstName() .' '. $message->getFrom()->getLastName();
        
        $text = "<b>Hai $nama</b>. Berikut ini list perintah yang dapat digunakan di bot Ilkom 2017 Reborn ini.\n\n";
        
        $text .= "<b>Bot Command</b>\n";
        $text .= "/start = Memulai Bot\n";
        $text .= "/help = Menampilkan daftar perintah.\n";
        $text .= "/about = Tentang Bot ini.\n\n";
        
        $text .= "<b>Akademik dan Informasi</b>\n";
        $text .= "/jadwal = Perintah untuk menampilkan Jadwal. Klik untuk lebih lanjut.\n";
        $text .= "/info = Perintah menampilkan informasi umum.\n";
        $text .= "/addinfo = Perintah untuk menambahkan informasi kepada yang lain.\n";
        $text .= "/beasiswa = Perintah untuk menampilkan info beasiswa.\n";
        $text .= "/addbeasiswa = Perintah untuk menambahkan informasi beasiswa kepada yang lain.\n\n";
        
        $text .= "<b>Fitur</b>\n";
        $text .= "/kbbi = Mencari definisi kata dari KBBI.\n";
        $text .= "/github = Fitur pencarian di github.\n";
        $text .= "/translate atau /tr = Terjemahan bahasa\n\n";
        
        $text .= "<b>Lainnya</b>\n";
        $text .= "/pesan = Mengirim kritik dan saran ke Bot, bisa juga digunakan untuk request fitur misalnya.\n";
        
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