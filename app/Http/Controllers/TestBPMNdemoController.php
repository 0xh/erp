<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Workflow;
use Encore\Admin\Workflow\Inject\Injector;
use Encore\Admin\Workflow\process\Process;
use Encore\Admin\Workflow\step\AutoStep;
use PHPMentors\Workflower\Definition\Bpmn2Reader;

class TestBPMNdemoController extends Controller
{
    public $test;
    protected $workflowRepository;

    public function __construct()
    {
        $this->workflowRepository = new WorkflowRepository();
    }

    public function test()
    {
        $std = new Student(10017, 0, 'Test', 0);
        $ctx = new Injector();
        $ctx->mapData(Student::class, $std);
        $ctx->mapData('acoin', 200);
        $prc = new Process($ctx);
        $step = new AutoStep($prc, 1,[],[],[]);
        $autoStep = new AutoStep($prc, 1,
                    [function ($acoin) {var_dump('check acoin');if ($acoin < 200) {return 'acoin too little';}},
                        function (Student $student) {var_dump($student);}],
                    [function (Student $student, $acoin) {$student->setAcoin($student->getAcoin() + $acoin);},
                        function () {var_dump('======>');}],
                    [function (Student $student) {if ($student->getAcoin() > 0) {$student->setStatus(1);}},
                        function (Student $student) {var_dump($student);}]
                );
        $result = $prc->first($step, array(2, 3),
            function (Student $student) {var_dump('if student.status == 1 then step 2 else step 3');return $student->getStatus() == 1 ? 2 : 3;}
        );

        $result->step(new AutoStep($prc, 2, [], [function () {var_dump('this is step 2');}], []));
        $result->step(new AutoStep($prc, 3, [],[function () {var_dump('this is step 3');}], []));
        $result->run();
        dd($prc,$result,$std);
        dd($result);
        if ($result !== true) {
            var_dump($prc->getStepNow()->getResult());
        }

        return 11;
    }

}



class Student
{
    private $id;
    private $acoin;
    private $nickname;
    private $status;

    /**
     * Student constructor.
     * @param $id
     * @param $acoin
     * @param $nickname
     * @param $status
     */
    public function __construct($id, $acoin, $nickname, $status)
    {
        $this->id = $id;
        $this->acoin = $acoin;
        $this->nickname = $nickname;
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getAcoin()
    {
        return $this->acoin;
    }

    /**
     * @param mixed $acoin
     */
    public function setAcoin($acoin)
    {
        $this->acoin = $acoin;
    }

    /**
     * @return mixed
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param mixed $nickname
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}
