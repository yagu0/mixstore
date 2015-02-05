<?php

namespace Mixstore\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildForm($builder, $options);

		// add your custom field
		$builder->remove('username');  // we use email as the username
		$builder->add('name'); // first name
		$builder->add('surname'); // last name
	}

	public function getName()
	{
		return 'mixstore_user_registration';
	}
}
