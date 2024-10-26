<?php
namespace YimoEx\Libs;

class HttpParam {
    
    private $data = [];

    public static function create($data = []){
        $param = new HttpParam();
        $param -> setAll($data);
        return $param;
    }

    public function __tostring(){
        return $this -> make();
    }

    public function makeJson(){
        return json_encode($this -> data);
    }

    public function make(){
        return http_build_query($this -> data);
    }

    public function set(string $key, string $value){
        $this -> data[$key] = $value;
    }

    public function setAll($data){
        $this -> data = $data;
    }

    public function getAll(){
        return $this -> data;
    }

}