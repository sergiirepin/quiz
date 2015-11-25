<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Answer
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="answer")
 */
class Answer
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Quiz")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     */
    private $quiz;

    /**
     * @ORM\ManyToOne(targetEntity="Question")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity="Choice")
     * @ORM\JoinColumn(name="choice_id", referencedColumnName="id")
     */
    private $choice;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_right;

    /**
     * @ORM\Column(type="time")
     */
    private $answer_time;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set isRight
     *
     * @param boolean $isRight
     *
     * @return Answer
     */
    public function setIsRight($isRight)
    {
        $this->is_right = $isRight;

        return $this;
    }

    /**
     * Get isRight
     *
     * @return boolean
     */
    public function getIsRight()
    {
        return $this->is_right;
    }

    /**
     * Set answerTime
     *
     * @param \DateTime $answerTime
     *
     * @return Answer
     */
    public function setAnswerTime($answerTime)
    {
        $this->answer_time = $answerTime;

        return $this;
    }

    /**
     * Get answerTime
     *
     * @return \DateTime
     */
    public function getAnswerTime()
    {
        return $this->answer_time;
    }

    /**
     * Set quiz
     *
     * @param \AppBundle\Entity\Quiz $quiz
     *
     * @return Answer
     */
    public function setQuiz(\AppBundle\Entity\Quiz $quiz = null)
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * Get quiz
     *
     * @return \AppBundle\Entity\Quiz
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * Set question
     *
     * @param \AppBundle\Entity\Question $question
     *
     * @return Answer
     */
    public function setQuestion(\AppBundle\Entity\Question $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \AppBundle\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set choice
     *
     * @param \AppBundle\Entity\Choice $choice
     *
     * @return Answer
     */
    public function setChoice(\AppBundle\Entity\Choice $choice = null)
    {
        $this->choice = $choice;

        return $this;
    }

    /**
     * Get choice
     *
     * @return \AppBundle\Entity\Choice
     */
    public function getChoice()
    {
        return $this->choice;
    }
}
