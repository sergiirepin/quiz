<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use AppBundle\Form\Type\QuestionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Question;
use AppBundle\Entity\Choice;
use Symfony\Component\Validator\Constraints\NotNull;

class QuestionQuizType extends QuestionType
{
    public function __construct($question, $choice)
    {
        parent::__construct($question, $choice);
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
				->add('next', 'submit', array('label' => 'Next question'))
		;
	}

	public function getName()
	{
		return 'app_quiz_question';
	}

}