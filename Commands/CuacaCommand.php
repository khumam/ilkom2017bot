<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class CuacaCommand extends UserCommand
{
    protected $name = 'cuaca';                      // Your command's name
    protected $description = 'Prediksi cuaca'; // Your command description
    protected $usage = '/cuaca [nama daerah]';                    // Usage of your command
    protected $version = '1.0.0';                  // Version of your command

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object

        $chat_id = $message->getChat()->getId();   // Get the current Chat ID
        $from_id = $message->getFrom()->getId();
        $nama = $message->getFrom()->getFirstName().' '.$message->getFrom()->getLastName();
        $id = trim($message->getText(true));
        $q = str_replace(' ', '%20', $id);
        
        // $conditions     = [
        //         'clear'        => '\xE2\x98\x80️',
        //         'clouds'       => '\xE2\x98\x81️',
        //         'rain'         => '\xE2\x98\x94',
        //         'drizzle'      => '\xE2\x9B\x85',
        //         'thunderstorm' => '\xE2\x9A\xA1',
        //         'snow'         => '\xE2\x9D\x84️',
        // ];
        
        $conditions     = [
                'clear'        => ' ☀️',
                'clouds'       => ' ☁️',
                'rain'         => ' ☔',
                'drizzle'      => ' ☔',
                'thunderstorm' => ' ⚡️',
                'snow'         => ' ❄️',
            ];
        
        if($id === ''){
            
            $text = "Gunakan perintah di bawah ini\n" . $this->getUsage();
            
            $kirimpesan = [
                    'chat_id' => $chat_id,
                    'parse_mode' => 'HTML',
                    'text' => $text
                ];
                
            return Request::sendMessage($kirimpesan);
        }
        
        else {
            
            $getdata = file_get_contents('https://api.openweathermap.org/data/2.5/weather?q='. $q .'&appid=APIKEY&units=metric');
            $decdata = json_decode($getdata, true);
            
            $getforcast = file_get_contents('https://api.openweathermap.org/data/2.5/forecast?q='. $q .'&appid=APIKEY&units=metric');
            $decfor = json_decode($getforcast, true);
            
            $stts = $decdata['cod'];
            
            $cuaca = $decdata['weather'][0]['main'];
            $desc = $decdata['weather'][0]['description'];
            $icon = $decdata['weather'][0]['icon'];
            $temp = $decdata['main']['temp'];
            $wind = $decdata['wind']['speed'];
            $cuacanow = $conditions[strtolower($cuaca)];
            
            $dtabsok = $decfor['list'];
            
                    $besok = $dtabsok[8]['weather'][0]['main'];
                    $descbesok = $dtabsok[8]['weather'][0]['description'];
                    $tempbesok = $dtabsok[8]['main']['temp'];
                    $windbesok = $dtabsok[8]['wind']['speed'];
            
            $cuacabesok = $conditions[strtolower($besok)];
            
            if($stts == '404'){
                
                $text = "Kota tidak ditemukan";
            } else {
                
                $text = "<b>Sekarang</b>\n$cuacanow $cuaca, $desc, $temp °С, Angin: $wind m/s";
                $text .= "\n\n";
                $text .= "<b>Besok</b>\n$cuacabesok $besok, $descbesok, $tempbesok °С, Angin: $windbesok m/s";
            }
            
            // Request::sendPhoto([
            //         'chat_id' => $chat_id,
            //         'photo' => "http://tile.openweathermap.org/map/clouds//3/109.04/-6.87.png?appid=APIKEY"
            //     ]);
            
            $kirimpesan = [
                    'chat_id' => $chat_id,
                    'parse_mode' => 'HTML',
                    'text' => $text
                ];
                
            return Request::sendMessage($kirimpesan);
        }
        
        
    }
}
