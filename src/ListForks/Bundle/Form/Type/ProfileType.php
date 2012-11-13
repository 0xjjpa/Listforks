<?php

namespace ListForks\Bundle\Form\Type;

use ListForks\Bundle\Form\Type\AccountType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('account', new AccountType());
		$builder->add('first_name', 'text');
		$builder->add('last_name', 'text');
		$builder->add('location', 'text');
	}

	public function getName()
	{
		return 'profile';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(
			array('data_class' => 'ListForks\Bundle\Entity\Profile'));
	}

	public function getDefaultOptions(array $options)
	{
		return array('data_class' => 'ListForks\Bundle\Entity\Profile');
	}
}