<?php
namespace YimoEx\Api;

use YimoEx\Libs\Request;
use YimoEx\Libs\HttpParam;

class Kimi extends Ai {
    
    const API = 'https://api.moonshot.cn/v1';

    const KEY = 'sk-xxx';

    public $model = 'moonshot-v1-8k';

}