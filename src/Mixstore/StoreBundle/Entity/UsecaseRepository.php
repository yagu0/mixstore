<?php

namespace Mixstore\StoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UsecaseRepository extends EntityRepository
{
	function safeFindById($id)
	{
		if ($id > 0)
		{
			$qb = $this->createQueryBuilder('u');
			$qb->where('u.id = '.$id);
			$usecase = $qb->getQuery()->getResult()[0];
		}
		else 
			$usecase = new Usecase();
		
		return $usecase;
	}
	
	function countByPkgId($pkgid)
	{
		return $this
			->createQueryBuilder('u')
			->select('COUNT(u.id) AS ucscount, AVG(u.grade) AS rating')
			->where('u.package = '.$pkgid)
			->getQuery()
			->getResult()[0];
			//->getSingleScalarResult();
	}
}
