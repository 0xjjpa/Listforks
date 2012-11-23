<?php

namespace ListForks\Bundle\Controller;

use ListForks\Bundle\Entity\ForkList;
use ListForks\Bundle\Entity\User;
use ListForks\Bundle\Entity\Item;
use ListForks\Bundle\Entity\Location;

use ListForks\Bundle\Form\Type\AccountType;
use ListForks\Bundle\Form\Type\UserType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

// imports the "@Secure" annotation
use JMS\SecurityExtraBundle\Annotation\Secure;

class ListsController extends Controller
{
	
	public function optionsListsAction()
    {        

        return new Response('[OPTIONS] /lists');

    //    return new Response('[OPTIONS] /lists');

    } // "options_lists" [OPTIONS] /lists


    /**
     * @Secure(roles="ROLE_USER")
     */
    public function getListsAction()
    {
        // Get current account
        $account = $this->get('security.context')->getToken()->getUser();

        // Find user in DB using account
        $user = $this->getDoctrine()
            ->getRepository('ListForksBundle:User')
            ->findOneByAccount($account);

        // User does not exist
        if( !$user )
        {
            // Create a JSON-response
            $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'We could not retrieve your lists.')));
        }
        // User exists
        else
        {
            // Get user's lists
            $forklists = $user->getForklists();

            // Empty array to store the user's lists
            $listArray = array();

            // Traverse through each list
            foreach( $forklists as $forklist )
            {
                // Get information for current list
                $id = $forklist->getId();
                $userId = $forklist->getUser()->getId();
                $name = $forklist->getName();
                $description = $forklist->getDescription();
                $private = $forklist->getPrivate();
                $location = $forklist->getLocation();
                $rating = $forklist->getRating();
                $items = $forklist->getItems();

                $createdDate = $forklist->getCreatedAt();
                $createdAt = $createdDate->format('D M d Y H:i:s (T)');
                $updatedDate = $forklist->getUpdatedAt();
                $updatedAt = $updatedDate->format('D M d Y H:i:s (T)');

                // Array to store the location co-ordinates of the current list
                $locationArray = array( 'latitude' => $location->getLatitude(),
                                        'longitude' => $location->getLongitude() );

                // Empty array to store the items of the current list
                $itemsArray = array();

                // Traverse through the items for the current list
                foreach( $items as $item )
                {
                    // Add item to itemArray
                    $itemsArray[] =  array( 'id' => $item->getId(),
                                            'description' => $item->getDescription() );
                }

                // Add list to listArray
                $listArray[] = array( '_hasData' => true,
                                      'createdAt' => $createdAt,
                                      'updatedAt' => $updatedAt,
                                      'attributes' => array( 'id' => $id,
                                                             'userId' => $userId,
                                                             'name' => $name,
                                                             'description' => $description,
                                                             'private' => $private,
                                                             'location' => $locationArray,
                                                             'rating' => $rating,
                                                             'items' => $itemsArray ));
            }

            // Create a JSON-response with the user's lists
            $response = new Response(json_encode($listArray));
        }

        // Set response headers
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    } // "get_lists"     [GET] /lists


    public function newListsAction()
    {
        return new Response('[GET] /lists/new');

    } // "new_lists"     [GET] /lists/new


    public function postListsAction()
    {
        // Get current request
        $request = $this->getRequest();
        // Get content associated with request
        $content = $request->getContent();

        // Check if content is empty
        if( !empty($content) )
        {
            // Empty array to store the list data
            $newListArray = array();

            // Convert JSON Request Object into an array 
            $newListArray = json_decode($content, true);

            // Get userId from request
            $userId = $newListArray['userId'];

            // Get current account
            $account = $this->get('security.context')->getToken()->getUser();

            // Find user in DB
            $user = $this->getDoctrine()
                ->getRepository('ListForksBundle:User')
                ->findOneByAccount($account);

            // Check if userId from request matches the userId associated with the current account
            if( $user->getId() == $userId )
            {
                 // Get list information from request
                 $listArray = $newListArray['list'];

                 // Get specific list info
                 $name = $listArray['name'];
                 $description = $listArray['description'];
                 $private = $listArray['private'];
                 $rating = $listArray['rating'];
                 $items = $listArray['items'];
                 $location = $listArray['location'];
                 $latitude = $listArray['location']['latitude'];
                 $longitude = $listArray['location']['longitude'];

                 // Create a new list
                 $forklist = new ForkList();

                 // Create a new location and associate it with the list
                 $newLocation = new Location();
                 $newLocation->setLatitude($latitude);
                 $newLocation->setLongitude($longitude);
                 $newLocation->setForklist($forklist);

                 // Bind list information to the list
                 $forklist->setName($name);
                 $forklist->setDescription($description);
                 $forklist->setPrivate($private);
                 $forklist->setLocation($newLocation);
                 $forklist->setRating($rating);
                 $forklist->setUser($user);

                 // Create items and associate it with the list
                 foreach( $items as $item )
                 {
                    $newItem = new Item();
                    $newItem->setDescription($item['description']);
                    $newItem->setComplete(false);
                    $newItem->setForklist($forklist);

                    $forklist->addItem($newItem);
                 }

                 // Associate the new list with the current user
                 $user->addForklist($forklist);

                 // Get current server date and time
                 $date = new \DateTime('now');
                 $createdAt = $date->format('D M d Y H:i:s (T)');
                 $updatedAt = $createdAt;

                 // Set timestamp for list
                 $forklist->setCreatedAt($date);
                 $forklist->setUpdatedAt($date);

                 // Persist changes to DB
                 $em = $this->getDoctrine()->getManager();
                 $em->persist($forklist);
                 $em->flush();

                 // Empty array to store the items of the list
                 $itemsArray = array();

                 // Get new items from DB
                 $newItems = $forklist->getItems();

                 foreach( $newItems as $newItem )
                 {
                    // Add item to itemArray
                    $itemsArray[] =  array( 'id' => $newItem->getId(),
                                            'description' => $newItem->getDescription() );
                 }

                 // Get id for new list
                 $id = $forklist->getId();

                 // Create an array with new list information to be returned
                 $returnList = array( '_hasData' => true,
                                      'createdAt' => $createdAt,
                                      'updatedAt' => $updatedAt,
                                      'attributes' => array( 'id' => $id,
                                                             'userId' => $userId,
                                                             'name' => $name,
                                                             'description' => $description,
                                                             'private' => $private,
                                                             'location' => $location,
                                                             'rating' => $rating,
                                                             'items' => $itemsArray ));

                 // Create a JSON response with the new list information
                 $response = new Response(json_encode($returnList)); 

            }
            // UserId does not match
            else
            {
                // Create a JSON response
                $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'We could not create your list.')));

            }
        }
        // Content is empty
        else
        {
            // Create a JSON response
            $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'We could not create your list.')));
            
        }

        // Set response headers
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    } // "post_lists"    [POST] /lists


    /**
     * @Secure(roles="ROLE_USER")
     */
    public function getListAction($id)
    {
        // Find list in DB using $id
        $forklist = $this->getDoctrine()
            ->getRepository('ListForksBundle:ForkList')
            ->find($id);

        // List does not exist
        if( !$forklist )
        {
            $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'No list found for id '.$id)));
        }
        // List exists
        else
        {
            // Get current account
            $account = $this->get('security.context')->getToken()->getUser();

            // Find user in DB using account
            $user = $this->getDoctrine()
                ->getRepository('ListForksBundle:User')
                ->findOneByAccount($account);

            // List is public or user is list owner
            if( $forklist->getPrivate() == false || $forklist->getUser()->getId() == $user->getId() )
            {
                // Get information for current list
                $id = $forklist->getId();
                $userId = $forklist->getUser()->getId();
                $name = $forklist->getName();
                $description = $forklist->getDescription();
                $private = $forklist->getPrivate();
                $location = $forklist->getLocation();
                $rating = $forklist->getRating();
                $items = $forklist->getItems();

                $createdDate = $forklist->getCreatedAt();
                $createdAt = $createdDate->format('D M d Y H:i:s (T)');
                $updatedDate = $forklist->getUpdatedAt();
                $updatedAt = $updatedDate->format('D M d Y H:i:s (T)');

                // Array to store the location co-ordinates of the current list
                $locationArray = array( 'latitude' => $location->getLatitude(),
                                        'longitude' => $location->getLongitude() );

                // Empty array to store the items of the current list
                $itemsArray = array();

                // Traverse through the items for the current list
                foreach( $items as $item )
                {
                    // Add item to itemArray
                    $itemsArray[] =  array( 'id' => $item->getId(),
                                            'description' => $item->getDescription() );
                }

                // Add list to listArray
                $listArray[] = array( '_hasData' => true,
                                      'createdAt' => $createdAt,
                                      'updatedAt' => $updatedAt,
                                      'attributes' => array( 'id' => $id,
                                                             'userId' => $userId,
                                                             'name' => $name,
                                                             'description' => $description,
                                                             'private' => $private,
                                                             'location' => $locationArray,
                                                             'rating' => $rating,
                                                             'items' => $itemsArray ));

                // Create a JSON-response with the user's list
                $response = new Response(json_encode($listArray));
            }
            // List is private and user is not the list owner
            else
            {
                $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'You do not have permission to view list id '.$id)));
            }
            
        }

        // Set response headers
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    } // "get_list"      [GET] /lists/{id}


    /**
     * @Secure(roles="ROLE_USER")
     */
    public function editListAction($id)
    {
        return new Response('[GET] /lists/'.$id.'/edit');

    } // "edit_list"     [GET] /lists/{id}/edit


    /**
     * @Secure(roles="ROLE_USER")
     */
    public function putListAction($id)
    {
        return new Response('[PUT] /lists/'.$id);

    } // "put_list"      [PUT] /lists/{id}


    /**
     * @Secure(roles="ROLE_USER")
     */
    public function deleteListAction($id)
    {

        // Find list in DB using $id
        $forklist = $this->getDoctrine()
            ->getRepository('ListForksBundle:ForkList')
            ->find($id);

        // List does not exist
        if( !$forklist )
        {

            // set error code 403  for login but no list exists.
            $response->setStatusCode(403);
            $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'No list found for id '.$id)));
        }
        // List exists
        else
        {
            // Get current account
            $account = $this->get('security.context')->getToken()->getUser();

            // Find user in DB using account
            $user = $this->getDoctrine()
                ->getRepository('ListForksBundle:User')
                ->findOneByAccount($account);

            // user is list owner
            if(  $forklist->getUser()->getId() == $user->getId() )
            {
                // Get information for current list
                $id = $forklist->getId();
                $userId = $forklist->getUser()->getId();
                $name = $forklist->getName();
                $description = $forklist->getDescription();
                $private = $forklist->getPrivate();
                $location = $forklist->getLocation();
                $rating = $forklist->getRating();
                $items = $forklist->getItems();

                // Array to store the location co-ordinates of the current list
                $locationArray = array( 'latitude' => $location->getLatitude(),
                                        'longitude' => $location->getLongitude() );

                // Empty array to store the items of the current list
                $itemsArray = array();

                // Traverse through the items for the current list
                foreach( $items as $item )
                {
                    // Add item to itemArray
                    $itemsArray[] =  array( 'id' => $item->getId(),
                                            'description' => $item->getDescription() );
                }

                // Add list to listArray
                $listArray[] = array( '_hasData' => true,
                                      'attributes' => array( 'id' => $id,
                                                             'userId' => $userId,
                                                             'name' => $name,
                                                             'description' => $description,
                                                             'private' => $private,
                                                             'location' => $locationArray,
                                                             'rating' => $rating,
                                                             'items' => $itemsArray ));


                // delete the user's list
                $em = $this->getDoctrine()->getManager();
                $em->remove($forklist);
                $em->remove($location);

                foreach( $items as $item )
                {
                    // Add item to itemArray
                    $em->remove($item);
                }

                
                $em->flush();




                // Create a JSON-response with the user's list
                $response = new Response(json_encode($listArray));
            }
            // List is private and user is not the list owner
            else
            {
                // 404 when user is not loged in or wrong permission
                $response->setStatusCode(404);
                $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'You don’t have permissions to delete this list' + 
                                  ' We couldn’t delete the list with the list Id provided.')));

            }
            
        }

        // Set response headers
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    } // "delete_list"   [DELETE] /lists/{id}


    /**
     * @Secure(roles="ROLE_USER")
     */
    public function getListItemsAction($id)
    {

        // Find list in DB using $id
        $forklist = $this->getDoctrine()
            ->getRepository('ListForksBundle:ForkList')
            ->find($id);

        // List does not exist
        if( !$forklist )
        {
            $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'No list found for id '.$id)));
        }
        // List exists
        else
        {
            // Get current account
            $account = $this->get('security.context')->getToken()->getUser();

            // Find user in DB using account
            $user = $this->getDoctrine()
                ->getRepository('ListForksBundle:User')
                ->findOneByAccount($account);

            // List is public or user is list owner
            if( $forklist->getPrivate() == false || $forklist->getUser()->getId() == $user->getId() )
            {
                // Get items for current list
                $items = $forklist->getItems();

                // Empty array to store the items of the current list
                $itemsArray = array();

                // Traverse through the items for the current list
                foreach( $items as $item )
                {
                    // Add item to itemArray
                    $itemsArray[] =  array( '_hasData' => true,
                                            'attributes' => array( 'id' => $item->getId(),
                                                                   'listId' => $forklist->getId(),
                                                                   'description' => $item->getDescription())); 
                }

                // Create a JSON-response with the requested list items
                $response = new Response(json_encode($itemsArray));
            }
            // List is private and user is not the list owner
            else
            {
                $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'You do not have permission to view list id '.$id)));
            }
            
        }

        // Set response headers
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    } // "get_list_items"    [GET] /lists/{id}/items


    public function newListItemsAction($id)
    {
        return new Response('[GET] /lists/'.$id.'/items/new');
    } // "new_list_items"    [GET] /lists/{id}/items/new


    /**
     * @Secure(roles="ROLE_USER")
     */
    public function getListItemAction($listId, $id)
    {
        // return new Response('[GET] /lists/'.$listId.'/items/'.$id);

        // Find list in DB using $listId
        $forklist = $this->getDoctrine()
            ->getRepository('ListForksBundle:ForkList')
            ->find($listId);

        // List does not exist
        if( !$forklist )
        {
            $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'No list found for id '.$listId)));
        }
        // List exists
        else
        {
            // Get current account
            $account = $this->get('security.context')->getToken()->getUser();

            // Find user in DB using account
            $user = $this->getDoctrine()
                ->getRepository('ListForksBundle:User')
                ->findOneByAccount($account);

            // List is public or user is list owner
            if( $forklist->getPrivate() == false || $forklist->getUser()->getId() == $user->getId() )
            {
                // Find item in DB using $id
                $item = $this->getDoctrine()
                    ->getRepository('ListForksBundle:Item')
                    ->find($id);

                // Item does not exist
                if( !$item )
                {
                    $response = new Response(
                        json_encode(array('_hasData' => false,
                                          'message' => 'No item found for id '.$id)));

                }
                // Item exists
                else
                {
                    // Check if item belongs to the requested list
                    if( $item->getForklist()->getId() == $forklist->getId() )
                    {

                        $itemArray = array( '_hasData' => true,
                                            'attributes' => array( 'id' => $item->getId(),
                                                                   'listId' => $forklist->getId(),
                                                                   'description' => $item->getDescription())); 

                        // Create a JSON-response with the requested list item
                        $response = new Response(json_encode($itemArray));

                    }
                    // Requested item does not belong to requested list
                    else
                    {
                        $response = new Response(
                            json_encode(array('_hasData' => false,
                                              'message' => 'We could not retrieve item id '.$id.' for the list id provided.')));

                    }

                }

            }
            // List is private and user is not the list owner
            else
            {
                $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'You do not have permission to view list id '.$id)));
            }
            
        }

        // Set response headers
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    } // "get_list_item"     [GET] /lists/{listId}/items/{id}








    public function getListForkAction($id)
    {
        return new Response('[GET] /lists/'.$id.'/fork');
    } // "get_user_forkedlist"    [GET] /lists/{id}/fork










    // post a list, an identical list gets created for thr user and the list is returned
    public function postListForkAction($id)
    {

        // Find list in DB using $id
        $forklist = $this->getDoctrine()
            ->getRepository('ListForksBundle:ForkList')
            ->find($id);

        // List does not exist
        if( !$forklist )
        {
            $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'No list found for id '.$id)));
        }
        // List exists
        else
        {
            // Get current account
            $account = $this->get('security.context')->getToken()->getUser();

            // Find user in DB using account
            $user = $this->getDoctrine()
                ->getRepository('ListForksBundle:User')
                ->findOneByAccount($account);

            // List is public or user is list owner
            if( $forklist->getPrivate() == false || $forklist->getUser()->getId() == $user->getId() )
            {
                // Get information for current list
                $id = $forklist->getId();
                $userId = $forklist->getUser()->getId();
                $name = $forklist->getName();
                $description = $forklist->getDescription();
                $private = $forklist->getPrivate();
                $location = $forklist->getLocation();
                $rating = $forklist->getRating();
                $items = $forklist->getItems();

                
                // set current time as the creation time
                $date = new DateTime('now');
                $createdAt = $date->format('D M d Y H:i:s (T)');
                // because we are goint to create a list right now, update date is the same
                $updatedDate = $createdDate;

                // Array to store the location co-ordinates of the current list
                $locationArray = array( 'latitude' => $location->getLatitude(),
                                        'longitude' => $location->getLongitude() );

                // Empty array to store the items of the current list
                $itemsArray = array();

                // Traverse through the items for the current list
                foreach( $items as $item )
                {
                    // Add item to itemArray
                    $itemsArray[] =  array( 'id' => $item->getId(),
                                            'description' => $item->getDescription() );
                }


                // --- Create a new list from retrived data ---
                 $forkedForklist = new ForkList();

                 // Create a new location and associate it with the list
                 $newLocation = new Location();
                 $newLocation->setLatitude($latitude->getLatitude());
                 $newLocation->setLongitude($longitude->getLongitude());
                 $newLocation->setForklist($forkedForklist);

                 // Bind list information to the list
                 $forkedForklist->setName($forklist->getName());
                 $forkedForklist->setDescription($forklist->getDescription());
                 $forkedForklist->setPrivate($forklist->getPrivate());
                 $forkedForklist->setLocation($forklist->getNewLocation());
                 $forkedForklist->setRating($forklist->getRating());
                 $forkedForklist->setUser($user);

                 // Create items and associate it with the list
                 foreach( $items as $item )
                 {
                    $newItem = new Item();
                    $newItem->setDescription($item->getDescription());
                    $newItem->setComplete(false);
                    $newItem->setForklist($forkedForklist);

                    $forkedForklist->addItem($newItem);
                 }

                 // Associate the new list with the current user
                 $user->addForklist($forkedForklist);

                 // Get current server date and time
                 $date = new DateTime('now');
                 $createdAt = $date->format('D M d Y H:i:s (T)');
                 $updatedAt = $createdAt;

                 // Set timestamp for list
                 $forkedForklist->setCreatedAt($date);
                 $forkedForklist->setUpdatedAt($date);

                 // Persist changes to DB
                 $em = $this->getDoctrine()->getManager();
                 $em->persist($forkedForklist);
                 $em->flush();


                // Add list to listArray
                $listArray[] = array( '_hasData' => true,
                                      'createdAt' => $createdAt,
                                      'updatedAt' => $updatedAt,
                                      'attributes' => array( 'id' => $id,
                                                             'userId' => $userId,
                                                             'name' => $name,
                                                             'description' => $description,
                                                             'private' => $private,
                                                             'location' => $locationArray,
                                                             'rating' => $rating,
                                                             'items' => $itemsArray ));

                // Create a JSON-response with the user's list
                $response = new Response(json_encode($listArray));
            }
            // List is private and user is not the list owner
            else
            {
                $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'You do not have permission to fork list id '.$id)));
            }
            
        }

        // Set response headers
        $response->headers->set('Content-Type', 'application/json');

        return $response;
        
    } // "new_user_forkedlist"    [POST] /lists/{id}/fork









    
    // isnt this same as remove a list ? i thought 
    // after we fork we simply make a new lis ?????
    public function deleteListForkAction($id)
    {
        return new Response('[DELETE] /lists/'.$id.'/fork');
    } // "delete_user_forkedlist"    [DELETE] /lists/{id}/fork









    public function getListWatchAction($id)
    {
        return new Response('[GET] /lists/'.$id.'/watch');
    } // "get_user_watchlist"    [GET] /lists/{id}/watch












    public function postListWatchAction($id)
    {
        return new Response('[POST] /lists/'.$id.'/watch');
    } // "post_user_watchlist"    [POST] /lists/{id}/watch














    public function deleteListWatchAction($id)
    {
        return new Response('[DELETE] /lists/'.$id.'/watch');
    } // "delete_user_watchedlist"    [DELETE] /lists/{id}/watch















    public function getListRateAction($id)
    {

         // Find list in DB using $id
        $forklist = $this->getDoctrine()
            ->getRepository('ListForksBundle:ForkList')
            ->find($id);

        // List does not exist
        if( !$forklist )
        {
            $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'No list found for id '.$id)));
        }
        // List exists
        else
        {
            // Get current account
            $account = $this->get('security.context')->getToken()->getUser();

            // Find user in DB using account
            $user = $this->getDoctrine()
                ->getRepository('ListForksBundle:User')
                ->findOneByAccount($account);

            // List is public or user is list owner
            if( $forklist->getPrivate() == false || $forklist->getUser()->getId() == $user->getId() )
            {
                $rating = $forklist->getRating();

                // Add list id and rating to listArray
                $listArray[] = array( '_hasData' => true,
                                      'id' => $id,
                                      'rating' => $rating );

                // Create a JSON-response with the user's list
                $response = new Response(json_encode($listArray));
            }
            // List is private and user is not the list owner
            else
            {
                $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'You do not have permission to access list id '.$id)));
            }
            
        }

        // Set response headers
        $response->headers->set('Content-Type', 'application/json');

        return $response;



    } // "get_user_rating"    [GET] /lists/{id}/rate

















    // !! !! !! to be fixed. can not read the value from request parameter.
    public function postListRateAction($id)
    {

        // Get current request
        $request = $this->getRequest();
        // Get content associated with request
        $content = $request->getContent();

        $rating = 4;

        // Check if content is empty
        if( !empty($content) )
        {
            // Empty array to store the list data
            $newListArray = array();

            // Convert JSON Request Object into an array 
            $newListArray = json_decode($content, true);


            // Get userId from request
            $userId = $newListArray['userId'];

            // Get current account
            $account = $this->get('security.context')->getToken()->getUser();

            // Find user in DB
            $user = $this->getDoctrine()
                ->getRepository('ListForksBundle:User')
                ->findOneByAccount($account);

            // Check if userId from request matches the userId associated with the current account
            if( $user->getId() == $userId )
            {
                 // Get list information from request
                 $listArray = $newListArray['list'];

                 // Get specific list info
                 $rating = $listArray['rating'];
            }

        }


        // if change to || it wnt include the cases when rating is sent as alpha numeric insted of number
        if ( ! ( $rating >= 0 && $rating <= 10 ) )
        {
                // set error code 403  for login but no list exists.
            $response->setStatusCode(416);
            $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'Requested Range Not Satisfiable, 0 <= Rate <= 10 ')));

        }
        else
        {
            
            // Find list in DB using $id
            $forklist = $this->getDoctrine()
                ->getRepository('ListForksBundle:ForkList')
                ->find($id);

            // List does not exist
            if( !$forklist )
            {

                // set error code 403  for login but no list exists.
                $response->setStatusCode(403);
                $response = new Response(
                    json_encode(array('_hasData' => false,
                                      'message' => 'No list found for id '.$id)));
            }
            // List exists
            else
            {
                // Get current account
                $account = $this->get('security.context')->getToken()->getUser();

                // Find user in DB using account
                $user = $this->getDoctrine()
                    ->getRepository('ListForksBundle:User')
                    ->findOneByAccount($account);

                // List is public or user is list owner
                if( $forklist->getPrivate() == false || $forklist->getUser()->getId() == $user->getId() )
                {
                    $forklist->setRating($rating);

                    // Get information for current list
                    $id = $forklist->getId();
                    $userId = $forklist->getUser()->getId();
                    $name = $forklist->getName();
                    $description = $forklist->getDescription();
                    $private = $forklist->getPrivate();
                    $location = $forklist->getLocation();    
                    $rating = $forklist->getRating();
                    $items = $forklist->getItems();

                    // Array to store the location co-ordinates of the current list
                    $locationArray = array( 'latitude' => $location->getLatitude(),
                                            'longitude' => $location->getLongitude() );

                    // Empty array to store the items of the current list
                    $itemsArray = array();

                    // Traverse through the items for the current list
                    foreach( $items as $item )
                    {
                        // Add item to itemArray
                        $itemsArray[] =  array( 'id' => $item->getId(),
                                                'description' => $item->getDescription() );
                    }

                    // Add list to listArray
                    $listArray[] = array( '_hasData' => true,
                                          'attributes' => array( 'id' => $id,
                                                                 'userId' => $userId,
                                                                 'name' => $name,
                                                                 'description' => $description,
                                                                 'private' => $private,
                                                                 'location' => $locationArray,
                                                                 'rating' => $rating,
                                                                 'items' => $itemsArray ));


                    // delete the user's list
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($forklist);
                    $em->flush();



                    
                    // Create a JSON-response with the user's list
                    $response = new Response(json_encode($listArray));
                }
                // List is private and user is not the list owner
                else
                {
                    // 404 when user is not loged in or wrong permission
                    $response->setStatusCode(404);
                    $response = new Response(
                    json_encode(array('_hasData' => false,
                                      'message' => 'You don’t have permissions to rate this list' + 
                                      ' We couldn’t rate the list with the list Id provided.')));
                }  
            }

        }
        

        // Set response headers
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    } // "new_user_rating"    [POST] /lists/{id}/rate
















    // Should we not implement this. once a user rate they can only change their rating. can delete.
    public function deleteListRateAction($id)
    {
        return new Response('[DELETE] /lists/'.$id.'/rate');
    } // "delete_user_rating"    [DELETE] /lists/{id}/rate



}