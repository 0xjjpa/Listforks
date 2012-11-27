<?php

namespace ListForks\Bundle\Controller;

use ListForks\Bundle\Entity\ForkList;
use ListForks\Bundle\Entity\User;
use ListForks\Bundle\Entity\Item;
use ListForks\Bundle\Entity\Rating;
use ListForks\Bundle\Entity\Location;
use ListForks\Bundle\Entity\Subscription;
use ListForks\Bundle\Model\Helpers;
use ListForks\Bundle\Form\Type\AccountType;
use ListForks\Bundle\Form\Type\UserType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

// imports the "@Secure" annotation
use JMS\SecurityExtraBundle\Annotation\Secure;


class SearchesController extends Controller
{

        /**
     * @Secure(roles="ROLE_USER")
     *
    *
    * @author Benjamin Akhtary
    *
    * @param searchTerm
    * @return the lists as json that have the search parameter in their name or description. 
    *
    * Sample request and response :  https://skydrive.live.com/#!/view.aspx?cid=B33E7327F5123B4D&resid=B33E7327F5123B4D%212246&app=Word
    *
     */
    // only retrive the watched list by the current user logged in on the specified list ( not watched for all list )
    public function getSearchAction($searchTerm)
    {

            
            $em = $this->getDoctrine()->getManager();
            // even if user does a SQL injection, the results is filtered only based on lists that he has access to in the cod to follow.
            $q = $em->createQuery("select u from ListForks\Bundle\Entity\ForkList u where u.name LIKE  '%".$searchTerm."%' OR u.description LIKE '%".$searchTerm ."%' ");
            $forklistArray = $q->getResult();
           
        // Get current account
        $account = $this->get('security.context')->getToken()->getUser();

        // Find user in DB using account
        $userLoggedIn = $this->getDoctrine()
            ->getRepository('ListForksBundle:User')
             ->findOneByAccount($account);

        $resultsArray = array();
        $responseArray = array();

        foreach ( $forklistArray as $forklist)
        {
            // if public or the owner show in the results
            if( $forklist->getPrivate() == false ||  $forklist->getUser()->getId() == $userLoggedIn->getId() )
            {
                

                
                $items = $forklist->getItems();
                $itemsArray = array();

                foreach( $items as $item )
                {
                                // Add item to itemArray
                       $tempItem =  array( 'id' => $item->getId(),
                                               'description' => $item->getDescription(),
                                               'order' => $item->getOrderNumber() );
                        array_push($itemsArray, $tempItem );
                }

                $location = array();

                if ( $forklist->getLocation())
                {
                    $location = array( 'latitude' => $forklist->getLocation()->getLatitude(),
                                          'longitude' => $forklist->getLocation()->getLongitude()
                                            );
                }
                // Add list to listArray
                            $forklistInfo = array( 'id' => $forklist->getId(),
                                                                         'userId' => $forklist->getUser()->getId(),
                                                                         'name' => $forklist->getName(),
                                                                         'description' => $forklist->getDescription(),
                                                                         'private' => $forklist->getPrivate(),
                                                                         'location' => $location,
                                                                         'rating' => $this->getRating($forklist),
                                                                         'items' => $itemsArray,
                                                                         'createdAt' => $forklist->getCreatedAt(),
                                                                         'updatedAt' => $forklist->getUpdatedAt()
                                                                          );


                array_push($resultsArray, $forklistInfo );



            }

        }

         // if there is atleast 1 subscription retrived from above
        if (count($resultsArray) > 0 ){
              // Add all lists to the response array
              $responseArray[] = array( '_hasData' => true,
                        'foundLists' => $resultsArray );
        }
        else
        {
              // Add all lists to the response array
              $responseArray[] = array( '_hasData' => false,
                        'foundLists' => $resultsArray );
        } 





                // Create a JSON-response with the user's list
                $response = new Response(json_encode($responseArray));

                return $response;

        
    } // "get_searchs"    [GET] /search/{searchTerm}



        // ******************* HELPERS METHODS **********************

    public function getRating($forklist)
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


    public function setRating($forklist, $user, $rate)
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