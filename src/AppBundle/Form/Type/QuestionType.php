<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Question;
use AppBundle\Entity\Choice;
use Symfony\Component\Validator\Constraints\NotNull;

class QuestionType extends AbstractType
{	
	protected $question;

	protected $choice;

	protected $questionsCount;

	protected $isMultiple;

	public function __construct($question, $choice, $size = null)
	{
		$this->question = $question;
		$this->choice = $choice;
		$this->questionsCount =$size;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$this->isMultiple = ($this->countRightAnswers() == 1) ? false : true;

		$builder->add(
				'choices', 
				'entity', 
				array(
					'class'	=> 'AppBundle:Choice',
					'constraints' => array(new NotNull()),
					'choice_label' => 'choice',
					'query_builder'	=> function(EntityRepository $er){
						return $er->createQueryBuilder('c')
							->where('c.question = :question')
							->setParameter('question', $this->question->getId())
							->orderBy('c.choiceMark')
						;
					},
					'multiple' => $this->isMultiple,
					'expanded' => true
				)
			)
			->add('check', 'submit', array('label' => 'Check answer'))
			->add('next', 'submit', array('label' => 'Next question'))
			->add('questionId', 'choice', array(
				'choices' => $this->getQuestionsRange(),
			))
		;
	}

	public function getName()
	{
		return 'app_question';
	}

	protected function countRightAnswers()
	{
		return count($this->choice->getQuestionChoicesContent($this->question->getId()));
	}

	protected function getQuestionsRange()
	{
		$questions = range(1, $this->questionsCount);
		array_unshift($questions, 'random');
		return $questions;
	}
}