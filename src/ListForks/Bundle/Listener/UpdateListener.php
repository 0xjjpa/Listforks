<?php

namespace ListForks\Bundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use ListForks\Bundle\Entity\Account;

class UpdateListener
{

	public function postPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();
		$entityManager = $args->getEntityManager();

		if( $entity instanceof Account )
		{
			$message = \Swift_Message::newInstance()
				->setSubject('Account Update Notification Test')
				->setFrom('notification@listforks.com')
				->setTo('raymond@listforks.com')
				->setBody($entity->getUsername());

			$transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
				->setUsername('notification@listforks.com')
				->setPassword('notification');

			$mailer = \Swift_Mailer::newInstance($transport);

			$mailer->send($message);

		}
	}

}