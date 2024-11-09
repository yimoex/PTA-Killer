<?php
namespace YimoEx\Pta;

use YimoEx\Base\Console;

class App {

    private $pta;
    private $ai;

    public function __construct($cookie){
        define('ROOT', dirname(__FILE__) . '/');
        $this -> pta = new Core('PTASession=' . $cookie);
        $this -> ai = new \YimoEx\Api\Controller();
    }

    public function init($ai_path = ROOT . '/api/models/'){
        $this -> ai -> init($ai_path);
        $this -> ai -> select(false, 'kimi');
    }

    public function scan(){
        $lastday = date('Y-m-d', time() - 864000);
        $res = json_decode($this -> pta -> problemSet(''), true);
        if($res == NULL) return false;
        if(!isset($res['total'])) exit('Error: 不能访问');
        $count = $res['total'];
        $sets = $res['problemSets'];
        Console::add('已扫描到' . $count . '个题集', 'App');
        $result = [];
        foreach($sets as $set){
            $result[] = Set::create($set);
        }
        return $result;
    }

    public function setStatus($set_id){
        $this -> pta -> selectProblemSet($set_id);
        $res = json_decode($this -> pta -> problemStatus(), true);
        if($res == NULL) return false;
        $res = $res['problemStatus'] ?? false;
        if(!$res) return false;
        $result = [];
        foreach($res as $problem){
            $result[] = Problem::create($problem);
        }
        return $result;
    }

    public function examProblem($set_id, $problem_id){
        $this -> pta -> selectProblemSet($set_id);
        $res = json_decode($this -> pta -> problemExamProgaming($problem_id), true);
        if($res == NULL) return false;
        return Program::create($res['problemSetProblem']);
    }

    public function examInfo($set_id){
        $this -> pta -> selectProblemSet($set_id);
        $res = json_decode($this -> pta -> problemExamInfo(), true);
        if($res == NULL) return false;
        return $res;
    }
    
    public function problemCount($set_id){
        $this -> pta -> selectProblemSet($set_id);
        $res = json_decode($this -> pta -> problemSummar(), true);
        if($res == NULL) return false;
        return $res['summaries'];
    }

    public function submit($exam_id, $problem_id, $progarm){
        $v = $this -> pta -> problemExamProgarmingSubmission($exam_id, $problem_id, $progarm);
        $res = json_decode($v, true);
        if($res == NULL) return false;
        return isset($res['submissionId']);
    }

    public function getLastSubmission($set_id, $problem_id){
        $this -> pta -> selectProblemSet($set_id);
        $v = $this -> pta -> progarmExamLastSubmission($problem_id);
        $res = json_decode($v, true);
        if($res == NULL) return false;
        return $res;
    }

    public function finishProgarm($progarm, $extMessage = ''){
        return $this -> ai -> send('帮我写以下问题的代码: ' . $progarm . $extMessage);
    }

    public function finishProgarmRaw($message){
        return $this -> ai -> send($message);
    }

    public function exec($caller, ...$param){
        return $this -> pta -> $caller(...$param);
    }

}