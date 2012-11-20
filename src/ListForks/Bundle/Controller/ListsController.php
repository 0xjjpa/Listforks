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


    public function getListItemsAction($id)
    {
        return new Response('[GET] /lists/'.$id.'/items');
    } // "get_user_comments"    [GET] /lists/{id}/items

    public function newListItemsAction($id)
    {
        return new Response('[GET] /lists/'.$id.'/items/new');
    } // "new_user_comments"    [GET] /lists/{id}/items/new

}