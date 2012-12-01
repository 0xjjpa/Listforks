<?php

namespace ListForks\Bundle\Tests\Entity;

use ListForks\Bundle\Entity\Account;
use ListForks\Bundle\Entity\ForkList;
use ListForks\Bundle\Entity\Item;
use ListForks\Bundle\Entity\Location;
use ListForks\Bundle\Entity\Preference;
use ListForks\Bundle\Entity\Subscription;
use ListForks\Bundle\Entity\Rating;
use ListForks\Bundle\Entity\User;

/**
 * ListForks\Bundle\Tests\Entity\UserTest
 *
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
	public function testUserName()
	{
		$user = new User();

		$firstName = 'First';
		$lastName = 'Last';

		$user->setFirstName($firstName);
		$user->setLastName($lastName);

		$this->assertEquals($user->getFirstName(), $firstName);
		$this->assertEquals($user->getLastName(), $lastName);
	}

	public function testUserLocation()
	{
		$user = new User();

		$location = 'Vancouver';

		$user->setLocation($location);

		$this->assertEquals($user->getLocation(), $location);
	}

	public function testUserAccount()
	{
		$user = new User();

		$account = new Account();

		$user->setAccount($account);

		$this->assertEquals($user->getAccount(), $account);
	}

	public function testUserLists()
	{
		$user = new User();

		$forklist = new ForkList();
		$forklist->setUser($user);

		$item = new Item();
		$item->setDescription('Item 1');
		$item->setComplete(false);
		$item->setOrderNumber(1);

		$forklist->addItem($item);

		$this->assertContains($item, $forklist->getItems());
		$this->assertEquals($item->getDescription(), 'Item 1');
		$this->assertFalse($item->getComplete());
		$this->assertEquals($item->getOrderNumber(), 1);

		$forklist2 = new ForkList();
		$forklist2->setUser($user);

		$forklist3 = new ForkList();
		$forklist3->setUser($user);

		$user->addForklist($forklist);
		$user->addForklist($forklist2);
		$user->addForklist($forklist3);

		$forklists = $user->getForklists();

		$this->assertContains($forklist, $forklists);
		$this->assertContains($forklist2, $forklists);
		$this->assertContains($forklist3, $forklists);

		$user->removeForklist($forklist2);

		$this->assertNotContains($forklist2, $forklists);
	}

	public function testUserSubscriptions()
	{

		$user = new User();

		$forklist = new ForkList();
		$forklist->setUser($user);

		$subscription = new Subscription();
		$subscription->setUser($user);
		$subscription->setForklist($forklist);

		$forklist->addSubscription($subscription);

		$user->addSubscription($subscription);

		$this->assertEquals($user, $subscription->getUser());
		$this->assertEquals($forklist, $subscription->getForklist());

		$this->assertContains($subscription, $user->getSubscriptions());

		$user->removeSubscription($subscription);

		$this->assertNotContains($subscription, $user->getSubscriptions());

	}

	public function testUserPreferences()
	{
		$user = new User();

		$preference = new Preference();
		$preference->setName('attachLocation');
		$preference->setDescription('Attach your current location to your lists.');
		$preference->setFlag(true);
		$preference->setUser($user);

		$preferenceFlag = $preference->getFlag();

		$user->addPreference($preference);

		$this->assertContains($preference, $user->getPreferences());

		$this->assertEquals($preference->getUser(), $user);
		$this->assertEquals($preference->getName(), 'attachLocation');
		$this->assertEquals($preference->getDescription(), 'Attach your current location to your lists.');
		$this->assertEquals($preference->getFlagToString($preferenceFlag), 'true');

		$preference2 = new Preference();
		$preference2->setName('notifyEmail');
		$preference2->setDescription('Receive an e-mail notification for subscribed list updates.');
		$preference2->setFlag(false);
		$preference2->setUser($user);

		$user->addPreference($preference2);
		$this->assertContains($preference2, $user->getPreferences());

		$user->removePreference($preference2);
		$this->assertNotContains($preference2, $user->getPreferences());
	}

	public function testUserRatings()
	{
		$user = new User();

		$forklist = new ForkList();

		$rating = new Rating();
		$rating->setRating(3);
		$rating->setUser($user);
		$rating->setForklist($forklist);

		$forklist->addRating($rating);

		$this->assertEquals($rating->getRating(), 3);
		$this->assertEquals($rating->getUser(), $user);
		$this->assertEquals($rating->getForklist(), $forklist);

		$rating2 = new Rating();
		$rating2->setRating(2);
		$rating2->setUser($user);

		$rating3 = new Rating();
		$rating3->setRating(4);
		$rating3->setUser($user);

		$user->addRating($rating);
		$user->addRating($rating2);
		$user->addRating($rating3);

		$ratings = $user->getRatings();

		$this->assertContains($rating, $ratings);
		$this->assertContains($rating2, $ratings);
		$this->assertContains($rating3, $ratings);

	}
}