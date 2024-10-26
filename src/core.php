<?php
namespace YimoEx\Pta;

use YimoEx\Libs\Request;
use YimoEx\Libs\HttpParam;

class Core {
    
    const API = 'https://pintia.cn/api';
    
    const TRUE_OR_FALSE = 'TRUE_OR_FALSE';
    const MULTIPLE_CHOICE = 'MULTIPLE_CHOICE';
    const PROGRAMMING = 'PROGRAMMING';

    private $problemSet; //被选择的题集
    public $cookie; //PTASession
    public $header;

    public function __construct($cookie){
        $this -> cookie = $cookie;
        $this -> header = [
            'accept: application/json',
            'content-type: application/json;charset=UTF-8',
        ];
    }

    public function info(){
        $api = self::API . '/u/current';
    }

    public function selectProblemSet($problemSet){
        $this -> problemSet = $problemSet;
    }

    //查询激活题集列表
    public function problemActiveSet($endDate, $limit = 10){
        $api = self::API . '/problem-sets';
        $param = HttpParam::create([
            'filter' => '{"endAtAfter":"' . $endDate . 'T16:00:00.000Z"}',
            'limit' => $limit,
            'order_by' => 'END_AT',
            'asc' => true
        ]);
        return $this -> request($api, $param);
    }

    public function problemSet($filter, $limit = 10){
        $api = self::API . '/problem-sets';
        $param = HttpParam::create([
            'filter' => $filter,
            'limit' => $limit,
            'order_by' => 'END_AT',
            'asc' => true
        ]);
        return $this -> request($api, $param);
    }

    //查询单个题集分数信息(filter: target_user_id)
    public function problemRank(array $filter){
        $api = self::API . '/problem-sets/' . $this -> problemSet . '/rankings?';
        $param = HttpParam::create($filter);
        return $this -> request($api, $param);
        //['target_user_id' => $user_id]
    }

    //查询单个题集的状态信息
    /*
     * score: 分数
     * problemPoolIndex: int(第几个题)
     * problemSubmissionStatus: PROBLEM_ACCEPTED(正确) / PROBLEM_WRONG_ANSWER(错误) / PROBLEM_NO_ANSWER(没回答)
     * problemType: TRUE_OR_FALSE(判断) / MULTIPLE_CHOICE(选择题) / PROGRAMMING(编程题)
     */
    public function problemStatus(){
        $api = self::API . '/problem-sets/' . $this -> problemSet . '/exam-problem-status?';
        return $this -> request($api, HttpParam::create([]));
    }

    //查询题型个数
    /**
     * summaries
     *  - type
     */
    public function problemSummar(){
        $api = self::API . '/problem-sets/' . $this -> problemSet . '/problem-summaries';
        return $this -> request($api, HttpParam::create([]));
    }

    //获取题集中的考题信息
    /**
     * id: ID
     * description: 描述
     * content: 内容 [最重要]
     * title: 内容
     * 
     */
    public function problemExam($exam_id, $problem_type){
        $api = self::API . '/problem-sets/' . $this -> problemSet . '/exam-problem?';
        $param = HttpParam::create([
            'exam_id' => $exam_id,
            'problem_type' => $problem_type,
        ]);
        return $this -> request($api, $param);
    }

    //获取编程题提交信息
    public function progarmExamLastSubmission($problem_id){
        $api = self::API . '/problem-sets/' . $this -> problemSet . '/exam-problem?';
        $param = HttpParam::create([
            'problem_set_problem_id' => $problem_id,
        ]);
        return $this -> request($api, $param);
    }

    //编程题，响应参数同上
    public function problemExamProgaming($progamingId){
        $api = self::API . '/problem-sets/' . $this -> problemSet . '/exam-problems/' . $progamingId;
        //var_dump($api);
        return $this -> request($api, HttpParam::create([]));
    }

    //题集中的题列表
    /**
     * 
     */
    public function problemExamList($exam_id, $problem_type){
        $api = self::API . '/problem-sets/' . $this -> problemSet . '/exam-problem-list/';
        $param = HttpParam::create([
            'exam_id' => $exam_id,
            'problem_type' => $problem_type,
            'page' => 0,
            'limit' => 100
        ]);
        return $this -> request($api, $param);
    }

    //获取题的提交ID等
    public function problemExamInfo(){
        $api = self::API . '/problem-sets/' . $this -> problemSet . '/exams';
        $param = HttpParam::create([]);
        return $this -> request($api, $param);
    }

    public function problemExamProgarmingSubmission($exam_id, $progarmId, $progarm){
        $api = self::API . '/exams/' . $exam_id . '/submissions';
        $param = HttpParam::create([
            'problemType' => 'PROGRAMMING',
            'details' => [$this -> problemExamObjCreate($progarmId, $progarm)]
        ]);
        return $this -> requestPostJson($api, $param);
    }

    private function problemExamObjCreate($id, $progarm){
        return [
            'problemId' => 0,
            'problemSetProblemId' => $id,
            'programmingSubmissionDetail' => [
                'compiler' => 'GCC',
                'program' => $progarm
            ]
        ];
    }

    private function request(string $api, HttpParam $param){
        $req = Request::create();
        $api .= '?' . $param -> make();
        $req -> init($api, $this -> header, $this -> cookie);
        return $req -> exec();
    }

    private function requestPostJson(string $api, HttpParam $param){
        $req = Request::create();
        $req -> init($api, $this -> header, $this -> cookie);
        $req -> setPost($param -> makeJson());
        return $req -> exec();
    }

}