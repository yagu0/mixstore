<?php

namespace Mixstore\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mixstore\StoreBundle\Entity\Package;
use Mixstore\StoreBundle\Entity\Language;
use Mixstore\StoreBundle\Entity\PackageRepository;
use Mixstore\StoreBundle\Entity\Usecase;
use Mixstore\StoreBundle\Entity\UsecaseRepository;

class PackageController extends Controller
{
	public function viewAction($id)
	{
		$em = $this
			->getDoctrine()
			->getManager();
		
		$package = $em
			->getRepository('MixstoreStoreBundle:Package')
			->find($id);
		
		//pass usecases count + rating (to link with feedbacks)
		$countAndRating = $em
			->getRepository('MixstoreStoreBundle:Usecase')
			->countByPkgId($id);
		if ($countAndRating['rating'] == '')
			$countAndRating['rating'] = 'NA';
		$package->rating = $countAndRating['rating'];
		$package->ucscount = $countAndRating['ucscount'];
		
		$user = $this->getUser();
		$owner = (!is_null($user) && $package->getUser()->getId() == $user->getId());
		
		return $this->render('MixstoreStoreBundle:Package:view.html.twig', array(
			'pkg' => $package,
			'owner' => $owner,
		));
	}
	
	public function listAction()
	{
		$em = $this
			->getDoctrine()
			->getManager();
		
		$packages = $em
			->getRepository('MixstoreStoreBundle:Package')
			->findAll();

		$ucsRepository = $em
			->getRepository('MixstoreStoreBundle:Usecase');
		
		for ($i=0; $i<count($packages); $i++)
		{
			$countAndRating = $ucsRepository->countByPkgId($packages[$i]->getId());
			if ($countAndRating['rating'] == '')
				$countAndRating['rating'] = 'NA';
			$packages[$i]->ucscount = $countAndRating['ucscount'];
			$packages[$i]->rating = $countAndRating['rating'];
		}
		
		return $this->render('MixstoreStoreBundle:Package:list.html.twig', array(
			'packages' => $packages,
		));
	}

	public function upsertAction($id)
	{
		$em = $this
			->getDoctrine()
			->getManager();
		
		$package = $em
			->getRepository('MixstoreStoreBundle:Package')
			->safeFindById($id);
		
		//TODO: place this at another location to avoid code redundancy
		$user = $this->getUser();
		if (is_null($user) || (
			!in_array('ROLE_ADMIN', $user->getRoles()) && 
			!is_null($package->getUser()) && 
			$package->getUser()->getId() != $this->getUser()->getId()))
		{
			//TODO: nice error page
			return $this->redirect($this->generateUrl('mixstore_static_home'));
		}

		$formBuilder = $this->createFormBuilder($package);
		$formBuilder
			->add('name', 'text')
			->add('headline', 'text')
			->add('url', 'text')
			->add('description', 'textarea', array('required' => false))
			->add('dependencies', 'text', array('required' => false))
			->add('authors', 'text')
			->add('contact', 'text')
			->add('language', 'entity', array(
				'class' => 'MixstoreStoreBundle:Language',
				'property' => 'name',
				'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
					return $er->createQueryBuilder('l')
						->orderBy('l.name', 'ASC');
				}))
			->add('bannerfile', 'file', array('label' => 'Banner (1000x300)', 'required' => false))
			->add('removebanner', 'checkbox', array('label' => 'Remove banner', 'required' => false));
		$form = $formBuilder->getForm();
		
		$request = $this->get('request');
		if ($request->getMethod() == 'POST') {
			// Link request <-> form ; $package contains data entered in the form
			$form->bind($request);
			if ($form->isValid()) {
				$package->setUser($this->getUser());
				$package->setModified(new \DateTime('now'));
				
				//HACK: text editor insert extra <p></p><br> tags for an empty description
				$description = $package->getDescription();
				if (trim(str_replace('<br>','', str_replace('<p>','', str_replace('</p>','', $description)))) == '')
					$package->setDescription('');
				
				$bannerfile = $form['bannerfile']->getData();
				$extension = (is_null($bannerfile) ? null : $bannerfile->guessExtension());
				
				$bannersDir = $this->get('kernel')->getRootDir().'/../web/mixstore/images/pkg_banners';
				
				if (!is_null($extension) && ($extension == 'jpeg' || $extension == 'png'))
				{
					if (is_null($package->getBannerPath()))
					{
						$filename = uniqid(rand(), true);
						$package->setBannerpath($filename);
					}
					else
						$filename = $package->getBannerpath(); 
					$bannerfile->move($bannersDir, $filename);
					
					//HACK: currently hard-coded
					$newwidth = 1000;
					$newheight = 300;
					
					$source = ($extension == 'jpeg' 
						? imagecreatefromjpeg($bannersDir.'/'.$filename)
						: imagecreatefrompng($bannersDir.'/'.$filename));

					$resizedFile = imagecreatetruecolor($newwidth, $newheight);
					list($width, $height) = getimagesize($bannersDir.'/'.$filename);
					imagecopyresized($resizedFile, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
					
					($extension == 'jpeg' 
						? imagejpeg($resizedFile, $bannersDir.'/'.$filename)
						: imagepng($resizedFile, $bannersDir.'/'.$filename));
					
					imagedestroy($resizedFile);
				}
				else if ($package->removebanner == TRUE)
				{
					unlink($bannersDir.'/'.$package->getBannerpath());
					$package->setBannerpath(null);
				}
						
				$em->persist($package);
				$em->flush();
				return $this->redirect($this->generateUrl('mixstore_store_package_view', array('id' => $package->getId())));
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
				->setSubject('Package '.($id > 0 ? 'update' : 'creation'))
				->setFrom('contact@mixstore.org')
				->setTo($receivers)
				->setBody($this->renderView('MixstoreStoreBundle::upsert-email.txt.twig', array(
					'name' => $this->getUser()->getName(),
					'surname' => $this->getUser()->getSurname(),
					'email' => $this->getUser()->getEmail(),
					'type' => 'Package',
					'id' => $package->getId(),
				)));
			$this->get('mailer')->send($message);
		}
		
		return $this->render('MixstoreStoreBundle::upsert.html.twig', array(
			'title' => ($id > 0 ? 'Edit package' : 'New package'),
			'form' => $form->createView(),
		));
	}
	
	public function deleteAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$package = $em
			->getRepository('MixstoreStoreBundle:Package')
			->findById($id)[0];
		
		$user = $this->getUser();
		if (is_null($user) || (
			!in_array('ROLE_ADMIN', $user->getRoles()) && 
			$package->getUser()->getId() != $this->getUser()->getId()))
		{
			//TODO: nice error page
			return $this->redirect($this->generateUrl('mixstore_static_home'));
		}
		
		$em->remove($package);
		$em->flush();
		return $this->redirect($this->generateUrl('mixstore_user_board'));
	}
}
