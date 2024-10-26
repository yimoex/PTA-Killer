<?php
use YimoEx\Base\Console;
use YimoEx\Pta\Problem;

include 'libs/request.php';
include 'libs/httpParam.php';

include 'base/console.php';
include 'base/base.php';
include 'base/set.php';
include 'base/progarm.php';
include 'base/problem.php';

include 'api/ai.php'; //require: httpParam/Request
include 'api/controller.php';

include 'core.php';
include 'app.php';

$app = new YimoEx\Pta\App('PTASession');
$app -> init();

foreach($app -> scan() as $set){
    $status = $set -> isTimeout() ? '截止了' : '没截止';
    $name = $set -> get('name');
    $set_id = $set -> get('id');
    Console::add('正在检测 [' . $name . '] => ' . $status . "【{$set_id}】", 'Main');
    if($set -> isTimeout()) continue;
    $problems = $app -> setStatus($set_id);
    if($problems == false){
        Console::add("貌似没有开始答案哦~ 已经自动跳过", 'Main');
        continue;
    }
    $exam = $app -> examInfo($set_id);
    $exam_id = $exam['exam']['id'];
    $total = $app -> problemCount(set_id: $set_id);
    $t_choice = $total['MULTIPLE_CHOICE'] ?? 0;
    $t_progarm = $total['PROGRAMMING'] ?? 0;
    $t_tf = $total['TRUE_OR_FALSE'] ?? 0;

    //由于PTA的设计特性，导致如果用[examProblem]接口会得不到[判断/选择]的代码块内容
    foreach($problems as $number => $problem){
        $type = $problem -> type();
        $number = $number + 1;
        if($type != Problem::TYPE_PROGAMMING) continue;
        if($problem -> status() == Problem::ACCEPT){
            Console::add("第{$number}题已经正确了,正在跳过", 'Main');
            continue;
        }
        $id = $problem -> get('id');
        $progarm = $app -> examProblem($set_id, $problem -> get('id'));
        $title = $progarm -> get('title');
        $content = $progarm -> get('content');
        Console::add("({$number})题目获取完毕!正在提交至AI", 'Main');

        $rec = substr($app -> finishProgarm($content), 4, -3);
        sleep(10);
        if($rec == NULL){
            Console::add("AI解答失败,正在研究中!", 'Main');
            exit(0);
        }else{
            Console::add("AI已解答,正在提交中!", 'Main');
        }
        $res = $app -> submit($exam_id, $id, $rec);
        Console::add("提交完成! 3秒后继续", 'Main');
        sleep(mt_rand(5, 10));
    }
    Console::nextLine();
}