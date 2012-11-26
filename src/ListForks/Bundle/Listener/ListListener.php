<?php

namespace ListForks\Bundle\Listener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use ListForks\Bundle\Entity\ForkList;
use ListForks\Bundle\Entity\Subscription;

class ListListener
{

	public function preUpdate(PreUpdateEventArgs $args)
	{
		$entity = $args->getEntity();
		$entityManager = $args->getEntityManager();

		if( $entity instanceof ForkList )
		{

			$transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
				->setUsername('notification@listforks.com')
				->setPassword('notification');

			$mailer = \Swift_Mailer::newInstance($transport);

			// List is public
			if( $entity->getPrivate() == false )
			{

				if( $args->hasChangedField('description') )
				{
					$items = $entity->getItems();

					$itemsString = "";

					foreach( $items as $item )
					{
						$itemsString = $itemsString.$item->getOrderNumber().". ".$item->getDescription()."\n";
					}

					$message = \Swift_Message::newInstance()
						->setSubject('ListForks.com: List Update Notification')
						->setFrom('notification@listforks.com')
						->setTo('raymond@listforks.com')
						->setBody("Notification of Change:\n\n" 
							."List Name: ".$entity->getName()."\n\n"
							."Description: ".$entity->getDescription()."\n\n"
							."Items:\n\n".$itemsString."\n\n"
							."Vist the list: "."http://massive-malia.pagodabox.com/app_dev.php/lists/".$entity->getId());

					$mailer->send($message);
				}

			}
			// List is private
			else
			{
				// Check if list was previously public
				if( $args->hasChangedField('private') )
				{
					// Compare old private value and new private value
					if( $args->getOldValue('private') == false && $args->getNewValue('private') == true )
					{
						// Get subscriptions for updated list
						$subscriptions = $entity->getSubscriptions();

						// Notify each subscriber that subscribed list has been made private
						foreach( $subscriptions as $subscription )
						{
							// Get e-mail address of subscribed user
							// $userEmail = $subscription->getUser()->getAccount()->getEmail();

							$message = \Swift_Message::newInstance()
								->setSubject('ListForks.com: List Update Notification')
								->setFrom('notification@listforks.com')
								->setTo('raymond@listforks.com')
								->setBody(
									'The list '.$entity->getName().' has been set to private by the list owner. As a result, you will no longer
									receive update notifications for this list.');

							$mailer->send($message);
							$forklist->removeSubscription($subscription);
						}

					}
				}

			}

		}
	}

}