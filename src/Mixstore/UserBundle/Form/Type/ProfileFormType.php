<?php

namespace Mixstore\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;
use Symfony\Component\Security\Core\SecurityContext;

class ProfileFormType extends BaseType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildForm($builder, $options);

		// add your custom field
		$builder->remove('username');  // we use email as the username
		$builder->add('name'); // first name
		$builder->add('surname'); // last name
		
		//$user = $this->securityContext->getToken()->getUser();
		// email notifications on packages/usecases creation
		$builder->add('emailnotif0', 'checkbox', array('label' => 'Email notifications', 'required' => false));
		// ...also on updates
		$builder->add('emailnotif1', 'checkbox', array('label' => 'Also on updates', 'required' => false));
	}

	public function getName()
	{
		return 'mixstore_user_profile';
	}
}
