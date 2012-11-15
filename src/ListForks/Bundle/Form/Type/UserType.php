<?php

namespace ListForks\Bundle\Form\Type;

use ListForks\Bundle\Form\Type\AccountType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
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
		return 'user';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(
			array('data_class' => 'ListForksBundle:User'));
	}

	public function getDefaultOptions(array $options)
	{
		return array('data_class' => 'ListForksBundle:User');
	}
}