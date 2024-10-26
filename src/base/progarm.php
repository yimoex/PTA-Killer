<?php
namespace YimoEx\Pta;

//题集-问题
class Progarm extends Base {

    public static function create(array $data){
        $pro = new Progarm();
        foreach($data as $k => $v){
            $pro -> set($k, $v);
        }
        return $pro;
    }

}
