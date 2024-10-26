<?php
namespace YimoEx\Api;

use YimoEx\Libs\Request;
use YimoEx\Libs\HttpParam;

class Ai {
    
    const API = 'https://api/v1';

    const KEY = 'sk-666';

    public $header = [
        'Authorization: Bearer ',
        'Content-Type: application/json'
    ];

    public $model = 'none';

    public static function create(){
        $c = new static();
        $c -> header[0] .= static::KEY;
        return $c;
    }

    public function send($message){
        $param = HttpParam::create([
            'model' => $this -> model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => '你现在是一个C语言编程者,并且会采取不同的变量名以防止跟别人的答案重合。你将会提供代码支持(要严格按照输入/输出格式来写,而且要注意特殊情况),你将只会输出代码(不含其他任何东西)'
                ],[
                    'role' => 'user',
                    'content' => $message
                ]
            ]
        ]);
        $api = static::API . '/chat/completions';
        $req = new Request();
        $req -> init($api, $this -> header);
        $req -> setPost($param -> makeJson());
        $res = json_decode($req -> exec(), true);
        if($res == NULL) return false;
        return $res['choices'][0]['message']['content'] ?? false;
    }
}