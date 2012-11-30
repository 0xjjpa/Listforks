<?php

namespace ListForks\Bundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountsControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();

        // $client = static::createClient(array(), array('HTTP_HOST' => 'localhost'));
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        // $response = $client->getResponse()->getContent();
        var_dump($crawler);

        // var_dump($crawler);

        // $this->assertTrue($crawler->filter('html:contains("username")')->count() > 0);

        //$form = $crawler->selectButton('login')->form();

        //$crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        //$crawler = $client->followRedirect();

        // $this->assertContains('Username', $response);
    }


    public function testAccounts()
    {
    	$client = static::createClient(array(), array(
    		'HTTP_HOST' => 'localhost',
    		'_username' => 'test',
    		'_password'   => 'test',
    		));

    	$crawler = $client->request('GET', '/accounts');

    	// var_dump($client->getResponse()->getContent());

    	// $this->assertTrue(
    	//	$client->getResponse()->headers->contains('Content-Type','application/json'));
    }
}
