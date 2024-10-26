<?php
namespace YimoEx\Pta;

class Base {

    private $data;

    public static function create(array $data){
        $base = new Base();
        foreach($data as $k => $v){
            $base -> set($k, $v);
        }
        return $base;
    }

    public function set($key, $value){
        $this -> data[$key] = $value;
    }

    public function get($key){
        return $this -> data[$key] ?? NULL;
    }

}