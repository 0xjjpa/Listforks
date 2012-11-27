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
    * @param None  GET
    * @return list of all the users, + count of their list that logged in user has access to
    * 
    * sample Request & Response :=> https://skydrive.live.com/#!/view.aspx?cid=B33E7327F5123B4D&resid=B33E7327F5123B4D%212248&app=Word
    */
    public function getUsersAction()
    {

         // Get current account
        $account = $this->get('security.context')->getToken()->getUser();

        // Find user in DB using account
        $userLoggedIn = $this->getDoctrine()
            ->getRepository('ListForksBundle:User')
            ->findOneByAccount($account);


        $usersArray = array();

        if ( $userLoggedIn)
        {
            
            $allUsers = $this->getDoctrine()
                ->getRepository('ListForksBundle:User')
                ->findAll();


            
            foreach( $allUsers as $user )
            {

                $numPublicList = 0;

                $usersList = $this->getDoctrine()
                    ->getRepository('ListForksBundle:ForkList')
                    ->findByUser($user);
                
                foreach ( $usersList as $list)
                {

                    if( $list->getPrivate() == false || $list->getUser()->getId() == $userLoggedIn->getId() )
                    {
                        $numPublicList = $numPublicList + 1 ;
                    }
                    
                }

                
                


                  // Add list to listArray
                  $userArray = array(  'userId' => $user->getId(),
                                         'firstName' => $user->getFirstName(),
                                         'lastName' => $user->getLastName(),
                                         'countList' => $numPublicList
                                       );
                  
                  $singledata = array( '_hasData' => true,
                                       'attributes' => $userArray );


                  array_push($usersArray, $singledata );
            }

        }

        $responseArray = array();

        $response;
         // if there is atleast 1 subscription retrived from above
         if (count($usersArray) > 0 ){
              // Create a JSON-response with the user's
              $response = new Response(json_encode($usersArray));


         }
         else
         {
              $singledata = array( '_hasData' => false,
                                       'attributes' => array() ); 
              // Create a JSON-response with the user's
              $response = new Response(json_encode($usersArray));
              // set error code 403  for login but not exists.
              $response->setStatusCode(403);
         }
        
         


         // Set response headers
        $response->headers->set('Content-Type', 'application/json');

        return $response;




    }// "get_all-users"    [GET] /users



    /*
    * @Secure(roles="ROLE_USER")
    *
    * @author Benjamin Akhtary
    *
    * @param give a user id
    * @return retrn the information for that user including fname, lname, number of 
    * 
    * sample Request & Response :   NOT FINISHED ! NOT REQUIRED NOW
    */
    public function getUserAction($id)
    {

        
         // Get current account
        $account = $this->get('security.context')->getToken()->getUser();

        // Find user in DB using account
        $userLoggedIn = $this->getDoctrine()
            ->getRepository('ListForksBundle:User')
            ->findOneByAccount($account);


        $usersArray = array();

        if ( $userLoggedIn)
        {
            
            $allUsers = $this->getDoctrine()
                ->getRepository('ListForksBundle:User')
                ->findAll();


            
            foreach( $allUsers as $user )
            {

                $numPublicList = 0;

                $usersList = $this->getDoctrine()
                    ->getRepository('ListForksBundle:ForkList')
                    ->findByUser($user);
                
                foreach ( $usersList as $list)
                {

                    if( $list->getPrivate() == false || $list->getUser()->getId() == $userLoggedIn->getId() )
                    {
                        $numPublicList = $numPublicList + 1 ;
                    }
                    
                }

                
                


                  // Add list to listArray
                  $userArray = array(  'userId' => $user->getId(),
                                         'firstName' => $user->getFirstName(),
                                         'lastName' => $user->getLastName(),
                                         'countList' => $numPublicList
                                       );
                  
                  $singledata = array( '_hasData' => true,
                                       'attributes' => $userArray );


                  array_push($usersArray, $singledata );
            }

        }

        $responseArray = array();

        $response;
         // if there is atleast 1 subscription retrived from above
         if (count($usersArray) > 0 ){
              // Create a JSON-response with the user's
              $response = new Response(json_encode($usersArray));


         }
         else
         {
              $singledata = array( '_hasData' => false,
                                       'attributes' => array() ); 
              // Create a JSON-response with the user's
              $response = new Response(json_encode($usersArray));
              // set error code 403  for login but not exists.
              $response->setStatusCode(403);
         }
        
         


         // Set response headers
        $response->headers->set('Content-Type', 'application/json');

        return $response;





    } // "get_user_by_id"    [GET] /user/1



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



            /**
     * @Secure(roles="ROLE_USER")
     *
    *
    * @author Benjamin Akhtary
    *
    * @param given the id of a user
    * @return all the lists the user has
    *
    * Sample request and response :=>  
    *
     */
    public function getUsersListsAction($id)
    {

        // Find list in DB using $id
        $userToGetLists = $this->getDoctrine()
            ->getRepository('ListForksBundle:User')
            ->find($id);

        // Get current account
        $account = $this->get('security.context')->getToken()->getUser();

        // Find user in DB using account
        $userLoggedIn = $this->getDoctrine()
            ->getRepository('ListForksBundle:User')
             ->findOneByAccount($account);

         $allLists = $this->getDoctrine()
              ->getRepository('ListForksBundle:ForkList')
              ->findByUser($userToGetLists);

        if ( $allLists )
        {
                
                // to store the result retrived from database
                $listsArray = array();

                foreach ( $allLists as $list)
                {
                    $forklist;

                    // if list is public or the owner is requesting
                    if( $list->getPrivate() == false || $list->getUser()->getId() == $userLoggedIn->getId() )
                    {
                        $forklist = $list;
                    }
                    // if the list of public as check above or the requester is the owner, proceed with returing the value
                    if ($forklist)
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
                            $itemArray =  array( 'id' => $item->getId(),
                                                    'description' => $item->getDescription(),
                                                    'order' => $item->getOrderNumber() );
                            array_push($itemsArray, $itemArray );
                        }

                        

                        // Add list to listArray
                        $listArray = array( '_hasData' => true,
                                            "createdAt" => $forklist->getCreatedAt()->format('D M d Y H:i:s (T)') ,
                                            "updatedAt" => $forklist->getUpdatedAt()->format('D M d Y H:i:s (T)')  ,
                                              'attributes' => array( 'listId' => $forklist->getId(),
                                                                     'userId' => $forklist->getUser()->getId(),
                                                                     'name' => $forklist->getName(),
                                                                     'description' => $forklist->getDescription(),
                                                                     'private' => $forklist->getPrivate(),
                                                              //       'location' => $locationArray,
                                                                     'rating' => $this->getRating($forklist),
                                                                     'items' => $itemsArray
                                                                      ));


                        array_push($listsArray, $listArray );


                        }
                    
                }

                // if there is atleast 1 subscription retrived from above
                if (count($listsArray) > 0 ){
                    // Add all lists to the response array
                    $responseArray[] = array( '_hasData' => true,
                                          'attributes' => $listsArray );
                }
                else
                {
                    // Add all lists to the response array
                    $responseArray[] = array( '_hasData' => fale,
                                          'attributes' => $listsArray );
                } 

                // Create a JSON-response with the user's list
                $response = new Response(json_encode($listsArray));

        }

        // List no subscriptions
        else
        {
            
            $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'No list found for user id '.$id)));

            // set error code 403  for login but not exists.
            $response->setStatusCode(403);

        }

        // Set response headers
        $response->headers->set('Content-Type', 'application/json');

        return $response;

        
    } // "get_user_lists"    [GET] /user/{id}/lists




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