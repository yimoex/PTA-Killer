<?php
namespace YimoEx\Pta;

//题集-问题
class Program extends Base {

    public static function create(array $data){
        $pro = new Program();
        foreach($data as $k => $v){
            $pro -> set($k, $v);
        }
        return $pro;
    }

}
