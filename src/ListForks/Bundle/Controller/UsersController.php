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
	
    /**
     * @Secure(roles="ROLE_USER")
     *
    *
    *
    * @param
    * @return
    *
    *
     */
	public function optionsUsersAction()
    {        

        return new Response('[OPTIONS] /users');

    //    return new Response('[OPTIONS] /users');

    } // "options_lists" [OPTIONS] /users


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
    * @param the list id to check if we are watching
    * @return
    *
    * Sample request and response :  https://skydrive.live.com/?cid=B33E7327F5123B4D&id=B33E7327F5123B4D%212188#!/view.aspx?cid=B33E7327F5123B4D&resid=B33E7327F5123B4D%212189&app=Word
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
                                                                     'rating' => $forklist->getRating(),
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


}