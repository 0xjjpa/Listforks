<?php

namespace ListForks\Bundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testError()
    {
    	$client = static::createClient();
    	$client->followRedirects(true);

    	$crawler = $client->request('GET', '/accounts/login');

    	$form = $crawler->selectButton('login')->form();

    	$crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        $crawler = $client->request('GET', '/test');

        $this->assertTrue($crawler->filter('html:contains("No route found")')->count() > 0);
    }
}
