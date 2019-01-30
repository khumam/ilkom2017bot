<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class JadwalCommand extends UserCommand
{
    protected $name = 'jadwal';                      
    protected $description = 'Informasi Jadwal';
    protected $usage = '/jadwal [hari:optional] | [rombel:optional]';                   
    protected $version = '1.0.0';                  

    public function execute()
    {
        $message = $this->getMessage();    
        
        Request::sendChatAction([
                'chat_id' => $chat_id,
                'action' => 'typing'
        ]);

        $chat_id = $message->getChat()->getId();   
        $from_id = $message->getFrom()->getId();
        $nama = $message->getFrom()->getFirstName() .' '. $message->getFrom()->getLastName();
        $nilai = trim($message->getText(true));
        $split = explode('|', $nilai);
        $hari = trim($split[0]);
        $rombel = trim($split[1]);
        
       
        
        if($nilai === ''){
            
            $data = file_get_contents('http://ilkomunnes.000webhostapp.com/api/jadwal/doddyrn');
            $decdata = json_decode($data, true);
            
            $status = $decdata['status'];
            $listjadwal = $decdata['data'];
            
            if($status == 'NotFound') {
                
                $text = "Hai $nama. Jadwal belum tersedia untuk saat ini";
            }
            
            else {
                
                $text .= "Hai $nama. Jadwal akan segera di-update";
            }
            
            $kirimpesan = [
                'chat_id' => $chat_id,
                'parse_mode' => 'HTML',
                'text' => $text
            ];
        
            return Request::sendMessage($kirimpesan);
        }
        
        elseif($nilai === 'all'){
            
            $data = file_get_contents('http://ilkomunnes.000webhostapp.com/api/jadwal/all/doddyrn');
            $decdata = json_decode($data, true);
            
            $status = $decdata['status'];
            $listjadwal = $decdata['data'];
            
            if($status == 'NotFound') {
                
                $text = "Hai $nama. List semua jadwal belum tersedia untuk saat ini";
            }
            
            else {
                
                $text .= "Hai $nama. List semua jadwal akan segera di-update";
            }
            
            $kirimpesan = [
                'chat_id' => $chat_id,
                'parse_mode' => 'HTML',
                'text' => $text
            ];
        
            return Request::sendMessage($kirimpesan);
            
        } 
        
        else {
            
            if($rombel){
                
                $data = file_get_contents('http://ilkomunnes.000webhostapp.com/api/jadwal/hari/'. $hari .'/rombel/'. $rombel .'/doddyrn');
                $decdata = json_decode($data, true); 
                
                $status = $decdata['status'];
                $listjadwal = $decdata['data'];
                
                if($status == 'NotFound') {
                    
                    $text = "Hai $nama. Jadwal pada hari $hari rombel $rombel belum tersedia untuk saat ini";
                }
                
                else {
                    
                    $text .= "Hai $nama. Jadwal untuk hari $hari rombel $rombel akan segera di-update";
                }
                
                $kirimpesan = [
                'chat_id' => $chat_id,
                'parse_mode' => 'HTML',
                'text' => $text
                ];
        
                return Request::sendMessage($kirimpesan);
            
            }
            
            else {
                
                if($hari == '1' || $hari == '2'){
                    
                    $data = file_get_contents('http://ilkomunnes.000webhostapp.com/api/jadwal/rombel/'. $hari .'/doddyrn');
                    $decdata = json_decode($data, true); 
                    
                    $status = $decdata['status'];
                    $listjadwal = $decdata['data'];
                    
                    if($status == 'NotFound') {
                        
                        $text = "Hai $nama. Jadwal untuk rombel $hari belum tersedia untuk saat ini";
                    }
                    
                    else {
                        
                        $text .= "Hai $nama. Jadwal untuk rombel $hari akan segera di-update";
                    }
                }
                
                else {
                    
                    $data = file_get_contents('http://ilkomunnes.000webhostapp.com/api/jadwal/hari/'. $hari .'/doddyrn');
                    $decdata = json_decode($data, true); 
                    
                    $status = $decdata['status'];
                    $listjadwal = $decdata['data'];
                    
                    if($status == 'NotFound') {
                        
                        $text = "Hai $nama. Jadwal pada hari $hari belum tersedia untuk saat ini";
                    }
                    
                    else {
                        
                        $text .= "Hai $nama. Jadwal pada hari $hari akan segera di-update";
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
        
      
    }
}