<?php

namespace AppBundle\Helper;

use Symfony\Component\HttpFoundation\Session\Session;

class Validation
{
    const QUIZ_OVERALL_TIME = 7200;

    protected $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function isCompleted()
    {
        if($this->session->get('questionsForAnswer') !== null && count($this->session->get('questionsForAnswer')) == 0){
            return true;
        }
        return false;
    }

    public function timeIsUp()
    {
        if(self::QUIZ_OVERALL_TIME <= $this->getElapsedTime()) {
            return true;
        }
        return false;
    }

    public function getElapsedTime()
    {
        return time() - (int)$this->session->get('startTime');
    }

    public function getQuizOverallTime()
    {
        return self::QUIZ_OVERALL_TIME;
    }

}