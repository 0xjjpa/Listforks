<?php

namespace ListForks\Bundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountsControllerTest extends WebTestCase
{

    public function testNewAccount()
    {
        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/new');

        $form = $crawler->selectButton('create')->form();

        

        $info = 'ftest5';
        $email = 'ftest5@test.com';

        $crawler = $client->submit($form, 
            array('username' => $info, 
                  'password' => $info,
                  'firstname' => 'First',
                  'lastname' => 'Last',
                  'email' => $email));

        $loginForm = $crawler->selectButton('login')->form();

        $crawler = $client->submit($loginForm, array('_username' => $info, '_password' => $info));


    }

    public function testLogin()
    {
        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $this->assertTrue($crawler->filter('html:contains("Username")')->count() > 0);

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        $crawler = $client->request('GET', '/main');

    }


    public function testAccounts()
    {
    	$client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $this->assertTrue($crawler->filter('html:contains("Username")')->count() > 0);

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        $crawler = $client->request('GET', '/accounts');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'));

    }


    public function testGetAccountId()
    {
        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $this->assertTrue($crawler->filter('html:contains("Username")')->count() > 0);

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'ftest2', '_password' => 'ftest2'));

        // Id does not exist
        $crawler = $client->request('GET', '/accounts/100');

        // Id exists
        $crawler = $client->request('GET', '/accounts/15');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'));
    }


    public function testPutAccountId()
    {
        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $this->assertTrue($crawler->filter('html:contains("Username")')->count() > 0);

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'ftest2', '_password' => 'ftest2'));

        $crawler = $client->request('PUT', '/accounts/2');

        $preferences = array();

        $preferences[] = array('id' => 27, 'name' => 'attachLocation', 'flag' => true);
        $preferences[] = array('id' => 28, 'name' => 'notifyEmail', 'flag' => true);

        // account id doesn't match
        $crawler = $client->request('PUT', '/accounts/14', array(), array(), array(), json_encode(
            array('accountId' => 1,
                  'email' => 'hello@test.com',
                  'firstName' => 'George',
                  'lastName' => 'Test',
                  'location' => 'Vancouver',
                  'preferences' => $preferences )));

        // invalid e-mail
        $crawler = $client->request('PUT', '/accounts/15', array(), array(), array(), json_encode( 
            array('accountId' => 15,
                  'email' => 'hellotest.com',
                  'firstName' => 'George',
                  'lastName' => 'Test',
                  'location' => 'Vancouver',
                  'preferences' => $preferences )));

        $crawler = $client->request('PUT', '/accounts/15', array(), array(), array(), json_encode( 
            array('accountId' => 15,
                  'email' => 'hihihi@test.com',
                  'firstName' => 'George',
                  'lastName' => 'Test',
                  'location' => 'Vancouver',
                  'preferences' => $preferences )));
    }

    public function testDeleteActionId()
    {
        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $this->assertTrue($crawler->filter('html:contains("Username")')->count() > 0);

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test8', '_password' => 'test'));

        $crawler = $client->request('DELETE', '/accounts/2');

        $crawler = $client->request('DELETE', '/accounts/12');
    }
}
