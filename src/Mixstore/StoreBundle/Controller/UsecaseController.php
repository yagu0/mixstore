<?php

namespace Mixstore\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mixstore\StoreBundle\Entity\Package;
use Mixstore\StoreBundle\Entity\PackageRepository;
use Mixstore\StoreBundle\Entity\Usecase;

class UsecaseController extends Controller
{
	public function viewAction($id)
	{
		$usecase = $this
			->getDoctrine()
			->getManager()
			->getRepository('MixstoreStoreBundle:Usecase')
			->find($id);
		
		$user = $this->getUser();
		$owner = (!is_null($user) && $usecase->getUser()->getId() == $user->getId());
		
		return $this->render('MixstoreStoreBundle:Usecase:view.html.twig', array(
			'ucs' => $usecase,
			'owner' => $owner,
		));
	}
	
	public function listAction($pkgid)
	{
		$em = $this
			->getDoctrine()
			->getManager();
		
		$pkgName = $em
			->getRepository('MixstoreStoreBundle:Package')
			->find($pkgid)
			->getName();
		
		return $this->render('MixstoreStoreBundle:Usecase:list.html.twig', array(
			'usecases' => $em
				->getRepository('MixstoreStoreBundle:Usecase')
				->findByPackage($pkgid),
			'pkgname' => $pkgName,
		));
	}

	public function upsertAction($id, $pkgid)
	{
		$em = $this
			->getDoctrine()
			->getManager();
		
		$usecase = $em
			->getRepository('MixstoreStoreBundle:Usecase')
			->safeFindById($id);
		
		//TODO: place this in an external service (used at several locations)
		$user = $this->getUser();
		if (is_null($user) || (
			!in_array('ROLE_ADMIN', $user->getRoles()) && 
			!is_null($usecase->getUser()) && 
			$usecase->getUser()->getId() != $this->getUser()->getId()))
		{
			//TODO: nice error page
			return $this->redirect($this->generateUrl('mixstore_static_home'));
		}
		
		//http://symfony.com/doc/current/reference/forms/types/entity.html
		if ($pkgid > 0)
		{
			$package = $em
				->getRepository('MixstoreStoreBundle:Package')
				->findById($pkgid);
		}

		$formBuilder = $this->createFormBuilder($usecase);
		if ($pkgid > 0)
		{
			$formBuilder
				->add('package', 'entity', array(
					'class' => 'MixstoreStoreBundle:Package',
					'choices' => $package,
					'property' => 'name',
					'read_only' => true));
		}
		else
		{
			$formBuilder
				->add('package', 'entity', array(
					'class' => 'MixstoreStoreBundle:Package',
					'property' => 'name'));
		}
		$formBuilder
			->add('institution', 'text')
			->add('headline', 'text')
			->add('description', 'textarea')
			->add('contact', 'text')
			->add('grade', 'choice', array('choices' => range(0, 10)));
		$form = $formBuilder->getForm();
		
		$request = $this->get('request');
		if ($request->getMethod() == 'POST') {
			// Link request <-> form ; $usecase contains data entered in the form
			$form->bind($request);
			if ($form->isValid()) {
				$usecase->setUser($this->getUser());
				$usecase->setModified(new \DateTime('now'));
				$em->persist($usecase);
				$em->flush();
				return $this->redirect($this->generateUrl('mixstore_store_usecase_view', array('id' => $usecase->getId())));
			}
		}
		
		//Email notifications [temporary ??!!]
		$users = $em
			->getRepository('MixstoreUserBundle:User')
			->findAll();
		$receivers = array();
		foreach ($users as $user)
		{
			if ($user->getEmailnotif1() || ($id <= 0 && $user->getEmailNotif0()))
				$receivers[] = $user->getEmail();
		}
		if (count($receivers) > 0)
		{
			$message = \Swift_Message::newInstance()
				->setSubject('Usecase '.($id > 0 ? 'update' : 'creation'))
				->setFrom('contact@mixstore.org')
				->setTo($receivers)
				->setBody($this->renderView('MixstoreStoreBundle::upsert-email.txt.twig', array(
					'name' => $this->getUser()->getName(),
					'surname' => $this->getUser()->getSurname(),
					'email' => $this->getUser()->getEmail(),
					'type' => 'Usecase',
					'id' => $usecase->getId(),
				)));
			$this->get('mailer')->send($message);
		}
		
		return $this->render('MixstoreStoreBundle::upsert.html.twig', array(
			'title' => ($id > 0 ? 'Edit usecase' : 'New usecase'),
			'form' => $form->createView(),
		));
	}
	
	public function deleteAction($id)
	{
		$usecase = $this
			->getDoctrine()
			->getManager()
			->getRepository('MixstoreStoreBundle:Usecase')
			->findById($id)[0];
		
		$user = $this->getUser();
		if (is_null($user) || (
			!in_array('ROLE_ADMIN', $user->getRoles()) && 
			$usecase->getUser()->getId() != $this->getUser()->getId()))
		{
			//TODO: nice error page
			return $this->redirect($this->generateUrl('mixstore_static_home'));
		}
		
		$em = $this->getDoctrine()->getManager();
		$em->remove($usecase);
		$em->flush();
		return $this->redirect($this->generateUrl('mixstore_user_board'));
	}
}
