<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;

class BukuCommand extends UserCommand
{
    protected $name = 'buku';                      
    protected $description = 'Mencari Buku'; 
    protected $usage = '/buku [judul]';                    
    protected $version = '2.0.0';                  

    public function execute()
    {
        $message = $this->getMessage() ?: $this->getCallbackQuery()->getMessage();            

        $chat_id = $message->getChat()->getId();   
        $from_id = $message->getFrom()->getId();
        $nama = $message->getFrom()->getFirstName().' '.$message->getFrom()->getLastName();
        $isi = trim($message->getText(true));
        $split = explode('|', $isi);
        $judul = trim($split[0]);
        $subjudul = trim($split[1]);
        
        Request::sendChatAction([
            'chat_id'=>$chat_id,
            'action'=>'typing'
            ]);
        
        if($isi === '') {
            
            $text = "Gunakan perintah dibawah ini untuk menggunakan fitur Pencarian buku\n\n";
            $text .= "Melihat buku terbaru\n<code>/buku baru</code> \n\n";
            $text .= "Mencari buku \n<code>/buku [kata kunci]</code> \n\n";
            $text .= "Melihat detail buku\n<code>/buku detail | [isbn]</code> \n\n";
            $text .= "Note: Buku yang ada adalah buku kategori IT semua.";
            
            $kirimpesan = [
                    'chat_id' => $chat_id,
                    'parse_mode' => 'HTML',
                    'text' => $text
                ];
            return Request::sendMessage($kirimpesan);
        }
        
        elseif($isi === 'baru') {
            
            $getdata = file_get_contents('https://api.itbook.store/1.0/new');
            $decdata = json_decode($getdata, true);
            
            $error = $decdata['error'];
            $total = $decdata['total'];
            $buku = $decdata['books'];
            $i = 1;
            
            if($error == '0'){
                
                $text = "Berikut $total buku terbaru\n\n";
                
                foreach ($buku as $books) :
                    
                    $text .= "<b>$i. " . $books['title'] . "</b>\n";
                    if(!empty($books['subtitle'])){
                        $text .= $books['subtitle'] . "\n";
                    }
                    $text .= "ISBN : <code>" . $books['isbn13'] . "</code> \n\n";
                    $i++;
                endforeach;
            } else {
                
                $text = "Tidak ada buku terbaru saat ini";
            }
            
            $kirimpesan = [
                    'chat_id' => $chat_id,
                    'parse_mode' => 'HTML',
                    'text' => $text
                ];
            return Request::sendMessage($kirimpesan);
        }
        
        else {
            
            if($judul == 'detail') {
                
                $getdata = file_get_contents('https://api.itbook.store/1.0/books/' . $subjudul);
                $decdata = json_decode($getdata, true);
                
                $error = $decdata['error'];
                $img = $decdata['image'];
                $books = $decdata;
                
                if($error == '0'){
                
                $text = "Berikut detail dari buku dengan ISBN $subjudul\n\n";
                
                    
                    $text .= "<b>" . $books['title']. "</b> \n";
                    $text .= $books['subtitle'] . "\n";
                    $text .= "(". $books['authors'] . ")\n\n";
                    $text .= $books['desc'] . "\n\n";
                    $text .= "Publisher : " . $books['publisher'] . "\n";
                    $text .= "Tahun : " . $books['year'] . "\n";
                    $text .= "Harga : " . $books['price']. "\n";
                    $text .= "ISBNr : " . $books['isbn13'] . "\n";
                    $text .= "Bahasa : " . $books['language'] . "\n";
                    $text .= "Jumlah Halaman : " . $books['pages'] . "\n";
                    $text .= "Rating : " . $books['rating'] . "\n\n";
                    $text .= "<a href='" . $books['url'] . "'>More Info</a>\n";
                    
                    Request::sendPhoto([
                            'chat_id' => $chat_id,
                            'photo' => $img
                        ]);
                
                }
                
                else {
                    
                    $text = "ISBN Tidak ditemukan";
                }
                
                $kirimpesan = [
                    'chat_id' => $chat_id,
                    'parse_mode' => 'HTML',
                    'text' => $text
                ];
                
                return Request::sendMessage($kirimpesan);
            }
            
            else {
                
                if($subjudul == '') {
                    $hal = 1;
                } else {
                    $hal = (int)$subjudul;
                }
                
                $getdata = file_get_contents('https://api.itbook.store/1.0/search/' . $judul .'/'.$hal);
                $decdata = json_decode($getdata, true);
                
                
                
                $error = $decdata['error'];
                $total = $decdata['total'];
                $pages = (int)$decdata['page'];
                $buku = $decdata['books'];
                $nextpage = $pages+1;
                $banyak = (int)$total;
                $max = ceil($banyak/10);
                
                $pagination = new InlineKeyboard([
                    
                    ['text' => 'Next', 'callback_data' => "bukucallbackhandler"],
                    
                    ]);
                
                $i = 1;
            
                if($error == '0'){
                    
                    if($hal<=$max){
                        $text = "Ditemukan $total buku dengan kata kunci $judul\n\n";
                    }
                    
                    elseif($hal>$max){
                        $text = "Tidak ditemukan atau melebihi batas halaman";
                    }
                    
                    foreach ($buku as $books) :
                        
                        $text .= "<b>$i. " . $books['title'] . "</b>\n";
                        if(!empty($books['subtitle'])){
                            $text .= $books['subtitle'] . "\n";
                        }
                        $text .= "ISBN : <code>" . $books['isbn13'] . "</code> \n\n";
                        $i++;
                    endforeach;
                    
                
               if($banyak>10){
                   
                   if($nextpage<=$max){
                       
                        $text .= "Untuk ke halaman selanjutnya, gunakan perintah ini\n<code>/buku $judul | " . $nextpage ."</code>";
                   } else {
                       
                       $text .= "Halaman terakhir";
                   }
                }
                    
                } else {
                    
                    $text = "Tidak ada buku dengan kata kunci $judul";
                }
                
                $kirimpesan = [
                        'chat_id' => $chat_id,
                        'parse_mode' => 'HTML',
                        // 'reply_markup' => $pagination,
                        'text' => $text
                    ];
                return Request::sendMessage($kirimpesan);
            }
        }

    }
}
