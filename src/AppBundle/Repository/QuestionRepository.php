<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class QuestionRepository extends EntityRepository
{
	public function size()
	{
		return $this->createQueryBuilder('q')
			->select('COUNT(q.id)')
			->getQuery()
			->getSingleScalarResult();
	}
}