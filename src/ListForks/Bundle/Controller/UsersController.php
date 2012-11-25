<?php

namespace ListForks\Bundle\Controller;

use ListForks\Bundle\Entity\ForkList;
use ListForks\Bundle\Entity\User;
use ListForks\Bundle\Entity\Item;
use ListForks\Bundle\Entity\Location;
use ListForks\Bundle\Entity\Subscription;

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
	

	public function optionsUsersAction()
    {        

        return new Response('[OPTIONS] /users');

    //    return new Response('[OPTIONS] /users');

    } // "options_lists" [OPTIONS] /users


    /**
    * @Secure(roles="ROLE_USER")
    */
    public function getUserSubscriptionsAction($id)
    {

       // Get current account
       $account = $this->get('security.context')->getToken()->getUser();


       // Find user in DB using account
       $userBeingSearched = $this->getDoctrine()
            ->getRepository('ListForksBundle:User')
            ->find($id);


       // Find user in DB using account
       $userLoggedIn = $this->getDoctrine()
            ->getRepository('ListForksBundle:User')
            ->findOneByAccount($account);

            
            $subscriptions = $this->getDoctrine()
                    ->getRepository('ListForksBundle:Subscription')
                    ->findByUser($userBeingSearched);


            $watchingLists = array();
            $watchListsPrepare = array();
            $listsArray = array();

            foreach ( $subscriptions as $subscription)
            {

                // if user being searched is the logged in user or the lists to retrive are public lists subscribed
                if( $subscription->getForklist()->getPrivate() == false || $subscription->getForklist()->getUser()->getId() == $userLoggedIn->getId() )
                {
                
          //          $watchingLists = $subscription->getForklist();

          //          foreach ( $watchingLists as $watchlist)
         //           {
             $watchlist = $subscription->getForklist();
                        

                        // Array to store the location co-ordinates of the current list
                        $locationArray = array( 'latitude' => $watchlist->getLocation()->getLatitude(),
                                            'longitude' => $watchlist->getLocation()->getLongitude()
                                            );

                        // Empty array to store the items of the current list
                        $itemsArray = array();
                        $items = $watchlist->getItems();

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
                                             'attributes' => array( 'id' => $watchlist->getId(),
                                                                     'userId' => $watchlist->getUser()->getId(),
                                                                     'name' => $watchlist->getName(),
                                                                     'description' => $watchlist->getDescription(),
                                                                     'private' => $watchlist->getPrivate(),
                                                                     'location' => $locationArray,
                                                                     'rating' => $watchlist->getRating(),
                                                                     'items' => $itemsArray,
                                                                     'createdAt' => $watchlist->getCreatedAt(),
                                                                     'updatedAt' => $watchlist->getUpdatedAt()
                                                                      ));


                        array_push($listsArray, $listArray );
         //           }

                    
                }
            }
            // Add all lists to the response array
            $responseArray[] = array( '_hasData' => true,
                                  'subscribedLists' => $listsArray );

            // Create a JSON-response with the user's list
            $response = new Response(json_encode($responseArray));
            

        // Set response headers
        $response->headers->set('Content-Type', 'application/json');

        return $response;



    } // "get_user_all-subscribed-lists"    [GET] /subscriptions






}