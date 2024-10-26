<?php
namespace YimoEx\Api;

use YimoEx\Base\Console;

class Controller {

    public $ai = [];

    public $current;

    public function init($path = './models/'){
        foreach($this -> scanner($path) as $scan){
            $raw = $this -> getFileName($scan);
            $name = ucfirst($raw);
            $class = '\\YimoEx\\Api\\' . ucfirst($raw);
            $file = $path . '/' . $scan;
            if(is_file($file)){
                include_once $file;
                $this -> ai[$raw] = $t = $class::create();
                Console::add("成功载入 [{$name}] AI模型[{$t -> model}]", 'Ai-Controller');
            }
        }
    }

    public function send($message){
        return $this -> current -> send($message);
    }

    public function select($isRandom = true, $name = ''){
        if($isRandom || !isset($this -> ai[$name])) return array_rand($this -> ai);
        return $this -> ai[$name];
    }

    public function scanner($path){
        $dp = opendir($path);
        $result = [];
        while(($res = readdir($dp)) !== false){
            if($res === '.' || $res === '..') continue;
            $result[] = $res;
        }
        return $result;
    }

    public function getFileName($file){
        $arr = explode('.', $file, 2);
        return $arr[0];
    }

}