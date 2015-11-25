<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="choice")
 */
class Choice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Question")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $question;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRight;

    /**
     * @ORM\Column(type="string")
     */
    private $choice;

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
     * Set isRightChoice
     *
     * @param boolean $isRightChoice
     *
     * @return Choice
     */
    public function setIsRightChoice($isRightChoice)
    {
        $this->isRight = $isRightChoice;

        return $this;
    }

    /**
     * Get isRightChoice
     *
     * @return boolean
     */
    public function getIsRightChoice()
    {
        return $this->isRight;
    }

    /**
     * Set choice
     *
     * @param string $choice
     *
     * @return Choice
     */
    public function setChoice($choice)
    {
        $this->choice = $choice;

        return $this;
    }

    /**
     * Get choice
     *
     * @return string
     */
    public function getChoice()
    {
        return $this->choice;
    }

    /**
     * Set isRight
     *
     * @param boolean $isRight
     *
     * @return Choice
     */
    public function setIsRight($isRight)
    {
        $this->isRight = $isRight;

        return $this;
    }

    /**
     * Get isRight
     *
     * @return boolean
     */
    public function getIsRight()
    {
        return $this->isRight;
    }

    /**
     * Set question
     *
     * @param \AppBundle\Entity\Question $question
     *
     * @return Choice
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
}
