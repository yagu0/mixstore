<?php

namespace Mixstore\StoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PackageRepository extends EntityRepository
{	
	function safeFindById($id)
	{
		if ($id > 0)
		{
			$qb = $this->createQueryBuilder('p');
			$qb->where('p.id = '.$id);
			$package = $qb->getQuery()->getResult()[0];
		}
		else 
			$package = new Package();
		
		return $package;
    }
    
    function getAllNames()
    {
		return $this
			->createQueryBuilder('p')
			->select('p.id, p.name')
			->getQuery()
			->getResult();
	}
	
	function getBannersUrls()
	{
		$bannersUrls = $this
			->createQueryBuilder('p')
			->select('p.id, p.bannerpath')
			->where('p.bannerpath IS NOT NULL')
			->getQuery()
			->getResult();
		$result = array();
		for ($i=0; $i<count($bannersUrls); $i++)
			$result[$bannersUrls[$i]['id']] = $bannersUrls[$i]['bannerpath'];
		return $result;
	}
	
	function getLastNews()
	{
		return $this
			->createQueryBuilder('p')
			->select('p.id, p.name, p.created')
			->orderBy('p.created', 'DESC')
			->setMaxResults(3) //currently hard-coded
			->getQuery()
			->getResult();
	}
}
