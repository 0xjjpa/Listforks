<?php

namespace ListForks\Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AccountType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('username', 'text');
        $builder->add('email', 'email');
        $builder->add('password', 'password');
	}

	public function getName()
	{
		return 'account';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(
			array('data_class' => 'ListForks\Bundle\Entity\Account'));
	}

	public function getDefaultOptions(array $options)
	{
		return array('data_class' => 'ListForks\Bundle\Entity\Account');
	}

}