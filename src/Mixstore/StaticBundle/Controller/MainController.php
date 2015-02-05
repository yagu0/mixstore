<?php

namespace Mixstore\StaticBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
	public function homeAction()
	{
		$pkgRepository = $this
			->getDoctrine()
			->getManager()
			->getRepository('MixstoreStoreBundle:Package');
		
		//get news
		$news = $pkgRepository
			->getLastNews();
		
		//get images sources for carousel
		$bannersArray = $pkgRepository
			->getBannersUrls();
		
		return $this->render('MixstoreStaticBundle::home.html.twig', array(
			'bannersArray' => $bannersArray,
			'news' => $news,
		));
	}
	
	public function aboutAction()
	{
		return $this->render('MixstoreStaticBundle::about.html.twig');
	}
	
	public function policyAction()
	{
		return $this->render('MixstoreStaticBundle::policy.html.twig');
	}
}
