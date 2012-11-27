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


class UsersController extends Controller
{
	


    /*
    * @Secure(roles="ROLE_USER")
    *
    * @author Benjamin Akhtary
    *
    * @param 
    * @return 
    * 
    * sample Request & Response : 
    */
    public function getUsersAction()
    {

         // Get current account
        $account = $this->get('security.context')->getToken()->getUser();

        // Find user in DB using account
        $userLoggedIn = $this->getDoctrine()
            ->getRepository('ListForksBundle:User')
            ->findOneByAccount($account);


        $users = array();

        if ( $userLoggedIn)
        {
            
            $allUsers = $this->getDoctrine()
                ->getRepository('ListForksBundle:User')
                ->findAll();


            
            foreach( $allUsers as $user )
            {
                  // Add list to listArray
                  $userArray[] = array(  'id' => $user->getId(),
                                         'name' => $user->getFirstName().$user->getLastName(),
                                         'countPublist' => 4
                                       );




                  array_push($users, $userArray );
            }

        }

        $responseArray = array();
         // if there is atleast 1 subscription retrived from above
         if (count($users) > 0 ){
              // Add all lists to the response array
               $responseArray[] = array( '_hasData' => true,
                                         'lists' => $users );
         }
         else
         {
              // Add all lists to the response array
              $responseArray[] = array( '_hasData' => fale,
                                    'lists' => $users );
              // set error code 403  for login but not exists.
              $response->setStatusCode(403);
         }
        
         // Create a JSON-response with the user's
         $response = new Response(json_encode($responseArray));


         // Set response headers
        $response->headers->set('Content-Type', 'application/json');

        return $response;




    }// "get_all-user_all-subscribed-lists"    [GET] /subscriptions



    /*
    * @Secure(roles="ROLE_USER")
    *
    * @author Benjamin Akhtary
    *
    * @param 
    * @return 
    * 
    * sample Request & Response : 
    */
    public function getUserAction($id)
    {

    } // "get_user_all-subscribed-lists"    [GET] /subscriptions



        /**
     * @Secure(roles="ROLE_USER")
     *
    *
    * @author Benjamin Akhtary
    *
    * @param given the id of a use
    * @return all the lists the user is subscribed if the requester has permission to view those lists.
    *
    * Sample request and response :=>  https://skydrive.live.com/?cid=B33E7327F5123B4D&id=B33E7327F5123B4D%212188#!/view.aspx?cid=B33E7327F5123B4D&resid=B33E7327F5123B4D%212189&app=Word
    *
     */
    // only retrive the watched list by the current user logged in on the specified list ( not watched for all list )
    public function getUserSubscriptionsAction($id)
    {

        // Find list in DB using $id
        $userToGetSubscriptions = $this->getDoctrine()
            ->getRepository('ListForksBundle:User')
            ->find($id);

        // Get current account
        $account = $this->get('security.context')->getToken()->getUser();

        // Find user in DB using account
        $userLoggedIn = $this->getDoctrine()
            ->getRepository('ListForksBundle:User')
             ->findOneByAccount($account);

            $subscriptions = $this->getDoctrine()
              ->getRepository('ListForksBundle:Subscription')
              ->findByUser($userToGetSubscriptions);

        if ( $subscriptions )
        {
                
                // to store the result retrived from database
                $listsArray = array();

                foreach ( $subscriptions as $subscription)
                {
                    $forklist = NULL;

                    // if list is public or the owner is requesting
                    if( $subscription->getForklist()->getPrivate() == false || $subscription->getForklist()->getUser()->getId() == $userLoggedIn->getId() )
                    {
                        $forklist = $subscription->getForklist();
                    }
                    // if the list of public as check above or the requester is the owner, proceed with returing the value
                    if ($forklist != NULL)
                    {
                        // Array to store the location co-ordinates of the current list
                        $locationArray = array( 'latitude' => $forklist->getLocation()->getLatitude(),
                                        'longitude' => $forklist->getLocation()->getLongitude()
                                        );

                        // Empty array to store the items of the current list
                        $itemsArray = array();
                        $items = $forklist->getItems();

                        // Traverse through the items for the current list
                        foreach( $items as $item )
                        {
                            // Add item to itemArray
                            $itemsArray[] =  array( 'id' => $item->getId(),
                                                    'description' => $item->getDescription(),
                                                    'order' => $item->getOrderNumber() );
                        }

                        

                        // Add list to listArray
                        $listArray[] = array( '_hasData' => true,
                                              'attributes' => array( 'id' => $forklist->getId(),
                                                                     'userId' => $forklist->getUser()->getId(),
                                                                     'name' => $forklist->getName(),
                                                                     'description' => $forklist->getDescription(),
                                                                     'private' => $forklist->getPrivate(),
                                                                     'location' => $locationArray,
                                                                     'rating' => $this->getRating($forklist),
                                                                     'items' => $itemsArray,
                                                                     'createdAt' => $forklist->getCreatedAt(),
                                                                     'updatedAt' => $forklist->getUpdatedAt()
                                                                      ));


                        array_push($listsArray, $listArray );


                        }
                    
                }

                // if there is atleast 1 subscription retrived from above
                if (count($listsArray) > 0 ){
                    // Add all lists to the response array
                    $responseArray[] = array( '_hasData' => true,
                                          'watchedLists' => $listsArray );
                }
                else
                {
                    // Add all lists to the response array
                    $responseArray[] = array( '_hasData' => fale,
                                          'watchedLists' => $listsArray );
                } 

                // Create a JSON-response with the user's list
                $response = new Response(json_encode($responseArray));

        }

        // List no subscriptions
        else
        {
            
            $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'No subscription found for user id '.$id)));

            // set error code 403  for login but not exists.
            $response->setStatusCode(403);

        }

        // Set response headers
        $response->headers->set('Content-Type', 'application/json');

        return $response;

        
    } // "get_user_forklists"    [GET] /users/{id}/forklists



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