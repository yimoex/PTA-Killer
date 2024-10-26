<?php
namespace YimoEx\Base;

class Console {

    private static $logs = [];

    public static function add($value, $caller = 'System'){
        echo "[{$caller}]: $value\n";
    }

    public static function put($value, $caller = 'System'){
        self::$logs[] = "[{$caller}]: $value\n";
    }

    public static function print(){
        foreach(self::$logs as $log){
            echo $log;
        }
    }

    public static function nextLine(){
        self::$logs[] = "\n";
    }

}