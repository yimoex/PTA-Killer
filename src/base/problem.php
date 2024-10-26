<?php
namespace YimoEx\Pta;

//题集-问题
class Problem extends Base {

    const ACCEPT = 0;
    const WRONG = 1;
    const NO_ANSWER = 2;

    const TYPE_CHOICE = 0;
    const TYPE_TF = 1;
    const TYPE_PROGAMMING = 2;

    public static function create(array $data){
        $pro = new Problem();
        foreach($data as $k => $v){
            $pro -> set($k, $v);
        }
        return $pro;
    }

    public function status(){
        $v = $this -> get('problemSubmissionStatus');
        if($v === 'PROBLEM_ACCEPTED') return self::ACCEPT;
        if($v === 'PROBLEM_WRONG_ANSWER') return self::WRONG;
        if($v === 'PROBLEM_NO_ANSWER') return self::NO_ANSWER;
        return -1;
    }

    public function type(){
        $v = $this -> get('problemType');
        if($v === 'TRUE_OR_FALSE') return self::TYPE_TF;
        if($v === 'MULTIPLE_CHOICE') return self::TYPE_CHOICE;
        if($v === 'PROGRAMMING') return self::TYPE_PROGAMMING;
        return -1;
    }

}