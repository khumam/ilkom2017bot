<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class GithubCommand extends UserCommand
{
    protected $name = 'github';                      // Your command's name
    protected $description = 'Github search'; // Your command description
    protected $usage = '/github [kata kunci]';                    // Usage of your command
    protected $version = '1.0.0';                  // Version of your command

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object

        $chat_id = $message->getChat()->getId();   // Get the current Chat ID
        $from_id = $message->getFrom()->getId();
        $nama = $message->getFrom()->getFirstName().' '.$message->getFrom()->getLastName();
        $trim = trim($message->getText(true));
        $split = explode('|', $trim);
        $key = trim($split[0]);
        $user = trim($split[1]);
        $q = str_replace(' ', '%20',$key);
        $u = str_replace(' ', '%20',$user);
        
        $opts = [
            'http' => [
                    'method' => 'GET',
                    'header' => [
                            'User-Agent: PHP',
                            'Time-Zone: Asia/Jakarta',
                                ]
                    ]
            ];
            
        $context = stream_context_create($opts);
        
        
        
        Request::sendChatAction([
                'chat_id' => $chat_id,
                'action' => 'typing'
            ]);
            
            
        if($trim === ''){
            
            $text = "Hai $nama. Gunakan perintah di bawah ini yah\n\n<code>" . $this->getUsage() ."</code>";
            $text .= "\n\nBerikut cara penggunaan perintah /github";
            $text .= "\n\n/github [nama repo] = Mencari repositories\n";
            $text .= "<code>Contoh /github telegeram bot</code>\n\n";
            $text .= "/github user:[namauser] = Mencari repositories yang dimiliki user tertentu\n";
            $text .= "<code>Contoh /github user:sahmura</code>\n\n";
            $text .= "/github user | [username] = Melihat detail user\n";
            $text .= "<code>Contoh /github user | sahmura</code>\n\n";
            $text .= "/github [nama repo] | [username] = Melihat detail repositories yang dimiliki user tertentu\n";
            $text .= "<code>Contoh /github MysqlLovePHP | sahmura</code>\n\n";
            
            $kirimpesan = [
                'chat_id' => $chat_id,
                'parse_mode' => 'HTML',
                'text' => $text
            ];
            
            return Request::sendMessage($kirimpesan);
        }
        
        else {
            
            //JIka ada split 1 (user)
            
            if($split[1]){
                
                
                if($key == 'user'){
                    
                    $data = file_get_contents('https://api.github.com/users/'. $user, false, $context);
                    $decdata = json_decode($data, true);
                    
                    $stts = $decdata['message'];
                    $login = $decdata['login'];
                    $fullname = $decdata['name'];
                    $htmlurl = $decdata['html_url'];
                    $ava = $decdata['avatar_url'];
                    $company = $decdata['company'];
                    $blog = $decdata['blog'];
                    $loc = $decdata['location'];
                    $bio = $decdata['bio'];
                    $repos = $decdata['public_repos'];
                    $gist = $decdata['public_gists'];
                    $follower = $decdata['followers'];
                    $following = $decdata['following'];
                    $create = str_replace('T', ', ', $decdata['created_at']);
                    $update = str_replace('T', ', ', $decdata['updated_at']);
                    
                    if(empty($decdata['login'])) {
                        
                        $text = "User $user Tidak ditemukan.";
                    }
                    
                    else {
                        
                        $text = "<a href='$htmlurl'>$fullname ($login)</a>\n$company\n$blog\n$loc\n\n";
                        $text .= "$bio\n\n";
                        $text .= "Repositories : " . $repos ."\n";
                        $text .= "Gists : " . $gist ."\n";
                        $text .= "Followers : " . $follower ."\n";
                        $text .= "Following : " . $following ."\n\n";
                        $text .= "Dibuat : " . $create ."\n";
                        $text .= "Terakhir Update : " . $update ."\n";
                        
                        Request::sendPhoto([
                            'chat_id' => $chat_id,
                            'photo'   => $ava,
                        ]);
                    }
                    
                    $kirimpesan = [
                            'chat_id' => $chat_id,
                            'parse_mode' => 'HTML',
                            'text' => $text
                        ];
                        
                    return Request::sendMessage($kirimpesan);
                }
                
                else {
                    
                    $data = file_get_contents('https://api.github.com/repos/'.$user.'/'.$q, false, $context);
                    $decdata = json_decode($data, true);
                    
                    $readme = base64_decode($decdata['content']);
                    
                    $stts = $decdata['message'];
                    $hasil = $decdata;
                    $fullname = $hasil['full_name'];
                    $reponame = $hasil['name'];
                    $author = $hasil['owner']['login'];
                    $linkauthor = $hasil['owner']['html_url'];
                    $linkrepo = $hasil['html_url'];
                    $des = $hasil['description'];
                    $create = str_replace('T', ' Jam ',$hasil['created_at']);
                    $update = str_replace('T', ' Jam ',$hasil['updated_at']);
                    $pushed = str_replace('T', ' Jam ',$hasil['pushed_at']);
                    $clone = $hasil['clone_url'];
                    $stars = $hasil['stargazers_count'];
                    $views = $hasil['watchers_count'];
                    $bhs = $hasil['language'];
                    $forks = $hasil['forks_count'];
                    $subs = $hasil['subscribers_count'];
                    
                    if(empty($hasil['full_name'])) {
                        
                        $text = "Repositories $key dari $user tidak ditemukan";
                    }
                    
                    if(!empty($decdata['content'])){
                        
                        $text = $readme;
                    }
                    
                    else {
                        
                        $text = "Berikut adalah deskripsi lengkap dari Repositories $fullname \n\n";
                        $text .= "<a href='$linkrepo'>$fullname</a>\n";
                        $text .= "<b>$author</b>\n($bhs)\n\n";
                        $text .= "$des\n\n";
                        $text .= "Dibuat : $create\nTerakhir Update : $update\nTerakhir Push : $pushed\n\n";
                        $text .= "Clone : $clone\n\n";
                        $text .= "\xE2\xAD\x90 $stars\n";
                        $text .= "\xF0\x9F\x91\x80 $views\n";
                        $text .= "\xF0\x9F\x94\x97 $forks (forks)\n";
                        $text .= "\xF0\x9F\x91\xA5 $subs\n";
                        
                    }
                    
                    $kirimpesan = [
                            'chat_id' => $chat_id,
                            'parse_mode' => 'HTML',
                            'text' => $text
                        ];
                        
                    return Request::sendMessage($kirimpesan);
                }
                
                
                
            } 
            
            else {
                
                $data = file_get_contents('https://api.github.com/search/repositories?q='. $q .'&page=1&per_page=10', false, $context);
                $decdata = json_decode($data, true);
                
                $total = $decdata['total_count'];
                $hasil = $decdata['items'];
                $i = 1;
                
                if($total == 0) {
                    
                    $text = "Repositories tidak ditemukan. Coba yang lain.";
                }
                
                else {
                    
                    foreach ($hasil as $hsl) :
                    $text .= "$i. <a href='". $hsl['html_url'] . "'>" . $hsl['full_name'] . "</a>\n";
                    $text .= $hsl['description'] . "\n";
                    $text .= "Bahasa : " . $hsl['language'] ."\n";
                    $text .= "\xE2\xAD\x90 " . $hsl['stargazers_count'] ."\n\n";
                    $i++;
                    endforeach;
                
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
