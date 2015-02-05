<?php

namespace Mixstore\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mixstore\StoreBundle\Entity\Language;

class MyAdminController extends Controller
{
	//helper
	private function checkSuperAdmin()
	{
		$sadmin = $this->getUser();
		if (is_null($sadmin) || !in_array('ROLE_SUPER_ADMIN', $sadmin->getRoles()))
			//TODO: nice error page
			return $this->redirect($this->generateUrl('mixstore_static_home'));
	}
	
	//set initial languages
	public function setLanguageAction()
	{
		$this->checkSuperAdmin();
		
		$em = $this->getDoctrine()->getManager();
		foreach (array('C', 'C#', 'C++', 'Fortran', 'Java', 'Julia', 'Lua', 'MATLAB', 'Octave', 'Python', 'R', 'Scilab') as $lgName)
			$em->persist(new Language($lgName));
		$em->flush();
		
		return new Response('OK');
	}
	
	public function addLanguageAction($lgname)
	{
		$this->checkSuperAdmin();
		
		$em = $this->getDoctrine()->getManager();
		$em->persist(new Language($lgname));
		$em->flush();
		
		return new Response('OK');
	}
	
	public function usersAction()
	{
		$this->checkSuperAdmin();
		
		return $this->render('MixstoreUserBundle:Admin:users.html.twig', array(
		'users' => $this
			->getDoctrine()
			->getManager()
			->getRepository('MixstoreUserBundle:User')
			->findAll(),
		));
	}
	
	public function toggleAction()
	{
		$this->checkSuperAdmin();
		
		//get id of user to toggle
		$userId = $this->getRequest()->get('id');
		//get user to toggle
		$em = $this->getDoctrine()->getManager();
		$user = $em
			->getRepository('MixstoreUserBundle:User')
			->findById($userId)[0];
		
		if (in_array('ROLE_ADMIN', $user->getRoles()))
			$user->setRoles(array('ROLE_USER'));
		else
			$user->setRoles(array('ROLE_ADMIN'));
		
		//save new state
		$em->persist($user);
		$em->flush();
		
		//dummy unused answer (no error case for the moment)
		return new Response('OK');
	}
	
	public function deleteAction($id)
	{
		$this->checkSuperAdmin();
		
		$em = $this->getDoctrine()->getManager();
		$user = $em
			->getRepository('MixstoreUserBundle:User')
			->findById($id)[0];
		$em->remove($user);
		$em->flush();
		
		return new Response('OK');
	}
	
	//unique usage: set super admin
	public function setsaAction()
	{
		$em = $this->getDoctrine()->getManager();
		$sadmin = $em
			->getRepository('MixstoreUserBundle:User')
			->findById(1)[0]; //HACK: hard-coded ID
		
		$sadmin->setRoles(array('ROLE_ADMIN', 'ROLE_SUPER_ADMIN'));
		$em->persist($sadmin);
		$em->flush();
		
		return new response('OK');
	}
}
