<?php

namespace ListForks\Bundle\Tests\Entity;

use ListForks\Bundle\Entity\Account;

/**
 * ListForks\Bundle\Tests\Entity\AccountTest
 *
 */
class AccountTest extends \PHPUnit_Framework_TestCase
{

	public function testUsername()
	{
		$account = new Account();

		$account->setUsername('testUser');

		$this->assertEquals($account->getUsername(), 'testUser');
	}

	public function testEmail()
	{
		$account = new Account();

		$account->setEmail('test@test.com');

		$this->assertEquals($account->getEmail(), 'test@test.com');
	}

	public function testSalt()
	{
		$account = new Account();

		$salt = $account->getSalt();

		$this->assertNotNull($salt);

		$salt2 = md5(uniqid(null, true));

		$account->setSalt($salt2);

		$this->assertNotEquals($salt, $salt2);
		$this->assertEquals($account->getSalt(), $salt2);
	}

	public function testPassword()
	{
		$account = new Account();

		$password = 'plain-text';

		$account->setPassword($password);

		$this->assertEquals($account->getPassword(), $password);
	}

	public function testRoles()
	{
		$account = new Account();

		$this->assertEquals($account->getRoles(), array('ROLE_USER'));
	}

}