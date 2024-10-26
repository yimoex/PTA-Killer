<?php
namespace YimoEx\Pta;

//题集
class Set extends Base {

    public static function create(array $data){
        $set = new Set();
        foreach($data as $k => $v){
            $set -> set($k, $v);
        }
        return $set;
    }

    public function isTimeout(){
        $timer = strtotime($this -> get('endAt'));
        return $timer < time();
    }


}