<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\Keyboard;

class StackCommand extends UserCommand
{
    protected $name = 'stack';                      
    protected $description = 'Mencari postingan di stackoverflow'; 
    protected $usage = '/stack [kata kunci]';                    
    protected $version = '1.0.0';                  

    public function execute()
    {
        $message = $this->getMessage();            

        $chat_id = $message->getChat()->getId();   
        $from_id = $message->getFrom()->getId();
        $nama = $message->getFrom()->getFirstName().' '.$message->getFrom()->getLastName();
        $isi = trim($message->getText(true));
        $split = explode('|', $isi);
        $page = trim($split[1]);
        $kunci = trim($split[0]);
        $key = str_replace(' ', '%20',$kunci);
        
        if($isi === '') {
            
            $text = "Gunakan perintah dibawah ini untuk mencari postingan di Stackoverflow\n\n" . $this->getUsage();
            $text .= "\n\n<b>Contoh:</b>\n<code>/stack bot telegram longman</code>";
            
            $kirimpesan = [
                    'chat_id' => $chat_id,
                    'parse_mode' => 'HTML',
                    'text' => $text
                ];
            return Request::sendMessage($kirimpesan);
        } 
        
        else {
          
            if($split[1]){
              
                $hal = (int)$page;
              
                $getdata = file_get_contents('http://bejoku.herokuapp.com/apis/stack?search='. $key .'&page='. $page);
                $decdata = json_decode($getdata, true);
              
                $hsl = $decdata['result'];
                $count = count($hsl['at']);
                $nextpage = $hal+1;
                $prevpage = $hal-1;
                $i = 1;
              
                if(!empty($decdata['result'])){
                      
                    $text = "Berikut hasil pencarian dari $kunci \n\n";
                          
                    for($d =0; $d<$count; $d++) {
                          
                        $text .= "$i. <a href='" . $hsl['url'][$d] . "'>" . $hsl['title'][$d] . "</a>\n";
                        $text .= $hsl['at'][$d] . "\n\n";
                        $i++;
                    }
                    
                    if($hal >=2 ){
                        
                        $pagin = new Keyboard(["/stack $kunci | $prevpage", "/stack $kunci | $nextpage"],['stop']);
                        $pagin->setResizeKeyboard(true)->setOneTimeKeyboard(true)->setSelective(false);
                        
                    } else {
                        
                        $pagin = new Keyboard(["/stack $kunci | $nextpage"],['stop']);
                        $pagin->setResizeKeyboard(true)->setOneTimeKeyboard(true)->setSelective(false);
                        
                    }
                    
                    $kirimpesan = [
                        'chat_id' => $chat_id,
                        'parse_mode' => 'HTML',
                        'text' => $text,
                        'reply_markup' => $pagin,
                        'disable_web_page_preview' => 1
                        ];
                        
                    return Request::sendMessage($kirimpesan);
                  
                } else {
                      
                    $text = "Tidak ditemukan pencarian dengan kata kunci $kunci";
                      
                    $kirimpesan = [
                        'chat_id' => $chat_id,
                        'parse_mode' => 'HTML',
                        'text' => $text,
                        
                    ];
                    
                    return Request::sendMessage($kirimpesan);
                    
                }

            } else {
                
                $getdata = file_get_contents('http://bejoku.herokuapp.com/apis/stack?search='. $key .'&page=1');
                $decdata = json_decode($getdata, true);
              
                $hsl = $decdata['result'];
                $count = count($hsl['at']);
                $nextpage = 2;
                $prevpage = $hal-1;
                $i = 1;
              
                if(!empty($decdata['result'])){
                      
                    $text = "Berikut hasil pencarian dari $kunci \n\n";
                          
                    for($d =0; $d<$count; $d++) {
                          
                        $text .= "$i. <a href='" . $hsl['url'][$d] . "'>" . $hsl['title'][$d] . "</a>\n";
                        $text .= $hsl['at'][$d] . "\n\n";
                        $i++;
                    }
                    
                        
                    $pagin = new Keyboard(["/stack $kunci | $nextpage"],['stop']);
                    $pagin->setResizeKeyboard(true)->setOneTimeKeyboard(true)->setSelective(false);

                    
                    $kirimpesan = [
                        'chat_id' => $chat_id,
                        'parse_mode' => 'HTML',
                        'text' => $text,
                        'reply_markup' => $pagin,
                        'disable_web_page_preview' => 1
                        ];
                        
                    return Request::sendMessage($kirimpesan);
                  
                } else {
                      
                    $text = "Tidak ditemukan pencarian dengan kata kunci $kunci";
                      
                    $kirimpesan = [
                        'chat_id' => $chat_id,
                        'parse_mode' => 'HTML',
                        'text' => $text,
                        
                    ];
                    
                    return Request::sendMessage($kirimpesan);
                    
                }
            }
        }
        
    }
}
