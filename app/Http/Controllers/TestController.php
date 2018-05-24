<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use PHPMentors\Workflower\Definition\Bpmn2Reader;
use PHPMentors\Workflower\Workflow\Event\StartEvent;
use PHPMentors\Workflower\Workflow\Participant\ParticipantInterface;
use PHPMentors\Workflower\Workflow\Resource\ResourceInterface;

class TestController extends Controller
{
    public $test;
    protected $workflowRepository;

    public function __construct()
    {
        $this->workflowRepository = new WorkflowRepository();
    }

    public function test()
    {
        $kiyoshiApp = new KiyoshiApp();
        $output = $kiyoshiApp->run();

        echo implode(' ', $output)."\n";

    }

}

class KiyoshiApp
{
    public function run()
    {
        $definitionPath =__DIR__.'/bpmn/kiyoshi.bpmn';
        $reader = new Bpmn2Reader();
        $workflow = $reader->read($definitionPath);

        $kiyoshi = new KiyoshiHikawa();
        $fun = new Fun();

        $records = [];
        $workflow->setProcessData(['record' => implode(',', $records), 'random' => rand()]);
        $workflow->start(new StartEvent('start', $workflow->getRole('kiyoshi_hikawa')));
        while ($workflow->isActive()) {
            $participant = $workflow->getCurrentFlowObject()->getRole()->getId() == 'kiyoshi_hikawa' ? $kiyoshi : $fun;

            if (false !== strpos($workflow->getCurrentFlowObject()->getId(), 'say_')) {
                $records[] = str_replace('say_', '', $workflow->getCurrentFlowObject()->getId());
            }
            $workflow->setProcessData(['record' => implode(',', $records), 'random' => rand()]);
            $workflow->allocateWorkItem($workflow->getCurrentFlowObject(), $participant);
            $workflow->startWorkItem($workflow->getCurrentFlowObject(), $participant);
            $workflow->completeWorkItem($workflow->getCurrentFlowObject(), $participant);
        }

        return $records;
    }
}


class KiyoshiHikawa implements ParticipantInterface
{
    /**
     * @var ResourceInterface
     */
    private $resource;

    public function getId()
    {
        return 'mr_kiyoshi_hikawa';
    }

    public function hasRole($role)
    {
        return $role == 'kiyoshi_hikawa';
    }

    public function setResource(ResourceInterface $resource)
    {
        $this->resource = $resource;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function getName()
    {
        return $this->getId();
    }
}

class Fun implements ParticipantInterface
{
    /**
     * @var ResourceInterface
     */
    private $resource;

    public function getId()
    {
        return 'just_a_fun';
    }

    public function hasRole($role)
    {
        return $role == 'fun';
    }

    public function setResource(ResourceInterface $resource)
    {
        $this->resource = $resource;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function getName()
    {
        return $this->getId();
    }

}
