<?php
namespace YimoEx\Libs;

class Request {

    public $ch;

    public static function create(){
        return new Request();
    }

    public function init(string $url, array $header = [], string $cookie = ''){
        $this -> ch = $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'YimoEx/1.0.0');
        return $this;
    }

    public function exec(object $callback = NULL){
        if($this -> ch == NULL) return false;
        $data = curl_exec($this -> ch);
        curl_close($this -> ch);
        if(curl_errno($this -> ch)){
            printf("[Error]: " . curl_error($this -> ch) . "\n");
        }
        if($callback == NULL) return $data;
        return $callback($data);
    }

    public function set(string $opt, mixed $value){
        curl_setopt($this -> ch, $opt, $value);
    }

    public function setPost(string $param){
        curl_setopt($this -> ch, CURLOPT_POST, true);
        curl_setopt($this -> ch, CURLOPT_POSTFIELDS, $param);
    }
    
}
