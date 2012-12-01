<?php

namespace ListForks\Bundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListsControllerTest extends WebTestCase
{

	public function testGetLists()
    {
        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $this->assertTrue($crawler->filter('html:contains("Username")')->count() > 0);

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

    }


    public function testGetListId()
    {
    	$client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $this->assertTrue($crawler->filter('html:contains("Username")')->count() > 0);

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        $crawler = $client->request('GET', '/lists/500');

        $crawler = $client->request('GET', '/lists/26');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'));
    }


    public function testCreateList()
    {
        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $this->assertTrue($crawler->filter('html:contains("Username")')->count() > 0);

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        // empty content
        $crawler = $client->request('POST', '/lists');

        $location = array('latitude' => 200, 'longitude' => -200);

        $items = array();

        $items[] = array('id' => 0, 'description' => 'Item Description', 'order' => 1);
        $items[] = array('id' => 0, 'description' => 'Item Description', 'order' => 2);
        $items[] = array('id' => 0, 'description' => 'Item Description', 'order' => 3);

        $list = array('name' => 'Hello',
                      'description' => 'Description',
                      'private' => false,
                      'location' => $location,
                      'rating' => 0,
                      'items' => $items);

        // account id doesn't match
        $crawler = $client->request('POST', '/lists', array(), array(), array(), json_encode(
            array('userId' => 1,
                  'list' => $list)));
    }


    public function testUpdateListId()
    {

        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $this->assertTrue($crawler->filter('html:contains("Username")')->count() > 0);

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        // empty content
        $crawler = $client->request('PUT', '/lists/300');

        $location = array('latitude' => 200, 'longitude' => -200);

        $items = array();

        $items[] = array('id' => 92, 'description' => 'Test Item Description', 'status' => 'updated', 'order' => 5);
        $items[] = array('id' => 93, 'description' => 'Hi Item Description', 'status' => 'updated', 'order' => 3);
        $items[] = array('id' => 0, 'description' => 'New Item Description', 'status' => 'new', 'order' => 6);

        $list = array('id' => 26,
                      'name' => 'Hello',
                      'description' => 'Description',
                      'private' => false,
                      'location' => $location,
                      'rating' => 0,
                      'items' => $items);

        // account id doesn't match
        $crawler = $client->request('PUT', '/lists/26', array(), array(), array(), json_encode(
            array('userId' => 1,
                  'list' => $list)));


    }


    public function testDeleteListId()
    {
        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $this->assertTrue($crawler->filter('html:contains("Username")')->count() > 0);

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        // list does not exist
        $crawler = $client->request('DELETE', '/lists/300');


        $crawler = $client->request('DELETE', '/lists/31');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'));
    }

    public function testGetListItems()
    {
        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $this->assertTrue($crawler->filter('html:contains("Username")')->count() > 0);

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        $crawler = $client->request('GET', '/lists/100/items');

        $crawler = $client->request('GET', '/lists/26/items');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'));

    }


    public function testPostListItemsId()
    {
        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $this->assertTrue($crawler->filter('html:contains("Username")')->count() > 0);

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        $items = array();

        $items[] = array('id' => 0, 'description' => 'New Item Description', 'status' => 'new', 'order' => 6);

        $crawler = $client->request('POST', '/lists/26/items');

        // account id doesn't match
        $crawler = $client->request('POST', '/lists/26/items', array(), array(), array(), json_encode(
            array('userId' => 1,
                  'listId' => 26,
                  'items' => $items)));

    }

    public function testPutListItemsId()
    {

        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $this->assertTrue($crawler->filter('html:contains("Username")')->count() > 0);

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        $items = array();

        $items[] = array('id' => 93, 'description' => '93 Item Description', 'status' => 'updated', 'order' => 7);

        $crawler = $client->request('PUT', '/lists/26/items');

        // account id doesn't match
        $crawler = $client->request('PUT', '/lists/26/items', array(), array(), array(), json_encode(
            array('userId' => 1,
                  'listId' => 26,
                  'items' => $items)));

    }


    public function testDeleteListItemsId()
    {

        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $this->assertTrue($crawler->filter('html:contains("Username")')->count() > 0);

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        $items = array();

        $items[] = array('id' => 138, 'description' => '138 Item Description', 'status' => 'deleted', 'order' => 7);

        $crawler = $client->request('DELETE', '/lists/39/items');

        // account id doesn't match
        $crawler = $client->request('DELETE', '/lists/39/items', array(), array(), array(), json_encode(
            array('userId' => 1,
                  'listId' => 39,
                  'items' => $items)));
    }

    public function testGetListItemsId()
    {
        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $this->assertTrue($crawler->filter('html:contains("Username")')->count() > 0);

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        $crawler = $client->request('GET', '/lists/100/items/94');
        
        $crawler = $client->request('GET', '/lists/26/items/94');   

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'));
    }

/*

    public function testPostListForkActionId()
    {

        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $this->assertTrue($crawler->filter('html:contains("Username")')->count() > 0);

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        $crawler = $client->request('POST', '/lists/26/fork');
    }

*/

    /*

    public function testPostListWatchActionId()
    {
        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $this->assertTrue($crawler->filter('html:contains("Username")')->count() > 0);

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        $crawler = $client->request('POST', '/lists/100/watch');

        $crawler = $client->request('POST', '/lists/26/watch');
    }

    public function testGetListWatchActionId()
    {

        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/accounts/login');

        $this->assertTrue($crawler->filter('html:contains("Username")')->count() > 0);

        $form = $crawler->selectButton('login')->form();

        $crawler = $client->submit($form, array('_username' => 'test', '_password' => 'test'));

        $crawler = $client->request('GET', '/lists/100/watch');

        $crawler = $client->request('GET', '/lists/26/watch');
    }

    */

}