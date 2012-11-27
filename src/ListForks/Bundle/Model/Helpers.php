<?php
    
namespace ListForks\Bundle\Model;

namespace ListForks\Bundle\Controller;

use ListForks\Bundle\Entity\ForkList;
use ListForks\Bundle\Entity\User;
use ListForks\Bundle\Entity\Item;
use ListForks\Bundle\Entity\Rating;
use ListForks\Bundle\Entity\Location;
use ListForks\Bundle\Entity\Subscription;

use ListForks\Bundle\Form\Type\AccountType;
use ListForks\Bundle\Form\Type\UserType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class Helpers
{

    // ******************* HELPERS METHODS **********************

    public static function getRating($forklist)
    {
        $allRrating = $this->getDoctrine()
            ->getRepository('ListForksBundle:Rating')
             ->findByForklist($forklist);
        
             $count = 0;
             $sumRatings = 0;
             $rating = 0;

             foreach ( $allRrating as $rating)
             {
                 $sumRatings = $sumRatings + $rating->getRating();
                 $count = $count + 1;
             }

             
             if ( $count != 0)
             {
                 $rating = round( $sumRatings / $count );
             }

        return $rating;
    }



    public static function setRating($forklist, $user, $rate)
    {

        $rating = new Rating();
        $rating->setUser($user);
        $rating->setForklist($forklist);
        $rating->setRating($rate);

        $allRrating = $this->getDoctrine()
            ->getRepository('ListForksBundle:Rating')
             ->findByForklist($forklist);
        
             $alreadyRated = FALSE;

             foreach ( $allRrating as $rating)
             {
                 if ( $rating->getUser()->getId() == $user->getId() )
                 {
                     $alreadyRated = TRUE;
                 }
             }

             if ( !$alreadyRated)
             {
                 // Persist changes to DB
                 $em = $this->getDoctrine()->getManager();
                 $em->persist($rating);
                 $em->flush();
             }

             return;
    }


}

?>
