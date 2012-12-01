<?php

namespace ListForks\Bundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersControllerTest extends WebTestCase
{
   public function testGetUsers()
   {

        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        $crawler = $client->request('GET', '/users');

   }

   public function testGetUsersId()
   {

        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        $crawler = $client->request('GET', '/users/1');

   }

   public function testGetUsersSubscriptions()
   {

        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        $crawler = $client->request('GET', '/users/1/subscriptions');

   }

   public function testGetUsersLists()
   {

        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        $crawler = $client->request('GET', '/users/1/lists');

   }
}
