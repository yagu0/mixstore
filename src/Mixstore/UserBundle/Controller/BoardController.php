<?php

namespace Mixstore\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BoardController extends Controller
{
	public function indexAction()
	{
		if (is_null($this->getUser()))
			//TODO: nice error page
			return $this->redirect($this->generateUrl('mixstore_static_home'));
		
		$em = $this
			->getDoctrine()
			->getManager();
		
		$ucsRepository = $em
			->getRepository('MixstoreStoreBundle:Usecase');
		
		$usecases = $ucsRepository
			->findByUser($this->getUser()->getId());
		
		for ($i=0; $i<count($usecases); $i++)
			$usecases[$i]->software = $usecases[$i]->getPackage()->getName();
		
		$packages = $em
			->getRepository('MixstoreStoreBundle:Package')
			->findByUser($this->getUser()->getId());
		
		//TODO: fix code redundancy (see PackageController.php)
		for ($i=0; $i<count($packages); $i++)
		{
			$countAndRating = $ucsRepository->countByPkgId($packages[$i]->getId());
			if (is_null($countAndRating['rating']) || $countAndRating['rating'] == '')
				$countAndRating['rating'] = 'NA';
			$packages[$i]->ucscount = $countAndRating['ucscount'];
			$packages[$i]->rating = $countAndRating['rating'];
		}
		
		return $this->render('MixstoreUserBundle:Board:index.html.twig', array(
			'packages' => $packages,
			'usecases' => $usecases,
		));
	}
}
