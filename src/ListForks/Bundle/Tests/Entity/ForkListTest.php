<?php

namespace ListForks\Bundle\Tests\Entity;

use ListForks\Bundle\Entity\ForkList;
use ListForks\Bundle\Entity\Item;
use ListForks\Bundle\Entity\Location;
use ListForks\Bundle\Entity\Subscription;
use ListForks\Bundle\Entity\Rating;
use ListForks\Bundle\Entity\User;

/**
 * ListForks\Bundle\Tests\Entity\ForkListTest
 *
 */
class ForkListTest extends \PHPUnit_Framework_TestCase
{
	public function testCreatedAt()
	{
		$forklist = new ForkList();

		// Get current server date and time
        $date = new \DateTime('now');

		$forklist->setCreatedAt($date);

		$this->assertEquals($date, $forklist->getCreatedAt());
	}

	public function testUpdatedAt()
	{
		$forklist = new ForkList();

		// Get current server date and time
        $date = new \DateTime('now');

		$forklist->setUpdatedAt($date);

		$this->assertEquals($date, $forklist->getUpdatedAt());
	}

	public function testName()
	{
		$forklist = new ForkList();

		$name = 'Cookie Recipe';

		$forklist->setName($name);

		$this->assertEquals($forklist->getName(), $name);
	}

	public function testDescription()
	{
		$forklist = new ForkList();

		$description = 'This is a test description.';

		$forklist->setDescription($description);

		$this->assertEquals($forklist->getDescription(), $description);
	}

	public function testPrivateFalse()
	{
		$forklist = new ForkList();

		$forklist->setPrivate(false);

		$this->assertFalse($forklist->getPrivate());
	}

	public function testPrivateTrue()
	{
		$forklist = new ForkList();

		$forklist->setPrivate(true);

		$this->assertTrue($forklist->getPrivate());
	}

	public function testPrivateToString()
	{
		$forklist = new ForkList();

		$forklist->setPrivate(true);

		$isPrivate = $forklist->getPrivate();

		$this->assertEquals($forklist->getPrivateToString($isPrivate), 'true');
	}

	public function testUser()
	{
		$forklist = new ForkList();

		$user = new User();

		$forklist->setUser($user);

		$user->addForklist($forklist);

		$this->assertEquals($forklist->getUser(), $user);
	}

	public function testItems()
	{
		$forklist = new ForkList();

		$item = new Item();
		$item->setDescription('Item 1');
		$item->setForklist($forklist);

		$this->assertEquals($item->getForklist(), $forklist);

		$item2 = new Item();
		$item2->setDescription('Item 2');
		$item2->setForklist($forklist);

		$item3 = new Item();
		$item3->setDescription('Item 3');
		$item3->setForklist($forklist);

		$forklist->addItem($item);
		$forklist->addItem($item2);
		$forklist->addItem($item3);

		$items = $forklist->getItems();

		$this->assertContains($item, $items);
		$this->assertContains($item2, $items);
		$this->assertContains($item3, $items);

		$forklist->removeItem($item2);

		$this->assertNotContains($item2, $items);
	}

	public function testLocation()
	{
		$forklist = new ForkList();

		$location = new Location();

		$latitude = 102.01228172;
		$longitude = -21.2918210;

		$location->setLatitude($latitude);
		$location->setLongitude($longitude);
		$location->setForklist($forklist);

		$forklist->setLocation($location);

		$this->assertEquals($location->getForklist(), $forklist);
		$this->assertEquals($forklist->getLocation()->getLatitude(), $latitude);
		$this->assertEquals($forklist->getLocation()->getLongitude(), $longitude);
	}

	public function testSubscription()
	{
		$forklist = new ForkList();

		$subscription = new Subscription();
		$subscription->setForklist($forklist);

		$forklist->addSubscription($subscription);

		$this->assertContains($subscription, $forklist->getSubscriptions());

		$forklist->removeSubscription($subscription);

		$this->assertNotContains($subscription, $forklist->getSubscriptions());
	}

	public function testRating()
	{
		$forklist = new ForkList();

		$rating = new Rating();
		$rating->setRating(3);
		$rating->setForklist($forklist);

		$rating2 = new Rating();
		$rating2->setRating(2);
		$rating2->setForklist($forklist);

		$rating3 = new Rating();
		$rating3->setRating(4);
		$rating3->setForklist($forklist);

		$forklist->addRating($rating);
		$forklist->addRating($rating2);
		$forklist->addRating($rating3);

		$ratings = $forklist->getRatings();

		$this->assertContains($rating, $ratings);
		$this->assertContains($rating2, $ratings);
		$this->assertContains($rating3, $ratings);

		$forklist->removeRating($rating2);

		$this->assertNotContains($rating2, $ratings);
	}
	
}