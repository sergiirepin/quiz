<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChoiceRepository")
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
     * @ORM\Column(type="string")
     */
    private $choiceMark;

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
     * @param string $question
     * @return Choice
     */
    public function setQuestion($question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set choiceMark
     *
     * @param string $choiceMark
     *
     * @return Choice
     */
    public function setChoiceMark($choiceMark)
    {
        $this->choiceMark = $choiceMark;

        return $this;
    }

    /**
     * Get choiceMark
     *
     * @return string
     */
    public function getChoiceMark()
    {
        return $this->choiceMark;
    }
}
