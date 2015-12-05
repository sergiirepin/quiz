<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ChoiceRepository extends EntityRepository
{
	public function getQuestionChoices($questionId)
	{
		$result = $this->createQueryBuilder('c')
			->select('c.id')
			->where('c.question = :question_id AND c.isRight = 1')
			->setParameter('question_id', $questionId)
			->getQuery()
			->getResult();

		return array_map('current', $result);
	}
}