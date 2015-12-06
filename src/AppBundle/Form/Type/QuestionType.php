<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Question;
use AppBundle\Entity\Choice;
use Symfony\Component\Validator\Constraints\NotNull;

class QuestionType extends AbstractType
{	
	private $question;


	public function __construct($question)
	{
		$this->question = $question;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('choices', 'entity', array(
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
				'multiple' => true,
				'expanded' => true
			))
			->add('check', 'submit', array('label' => 'Check answer'))
		;
	}

	public function getName()
	{
		return 'app_question';
	}
}