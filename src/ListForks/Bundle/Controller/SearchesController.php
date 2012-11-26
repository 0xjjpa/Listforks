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


class SearchesController extends Controller
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
	public function optionsSearchesAction()
    {        

        return new Response('[OPTIONS] /search');

    //    return new Response('[OPTIONS] /search');

    } // "options_lists" [OPTIONS] /search


        /**
     * @Secure(roles="ROLE_USER")
     *
    *
    * @author Benjamin Akhtary
    *
    * @param search term
    * @return the lists as json that have the search parameter in their name or description. 
    *
    * Sample request and response :  https://skydrive.live.com/?cid=B33E7327F5123B4D&id=B33E7327F5123B4D%212188#!/view.aspx?cid=B33E7327F5123B4D&resid=B33E7327F5123B4D%212189&app=Word
    *
     */
    // only retrive the watched list by the current user logged in on the specified list ( not watched for all list )
    public function getSearchAction($searchTerm)
    {
        /*
        $forklistArray = $this->getDoctrine()
            ->getRepository('ListForksBundle:ForkList')->findByName( $searchTerm); 
        */
           // ->findBy(array("name" ));

            $em = $this->getDoctrine()->getManager();
            $q = $em->createQuery("select u from ListForks\Bundle\Entity\ForkList u where u.name LIKE  '%".$searchTerm."%' OR u.description LIKE '%".$searchTerm ."%' ");
            $forklistArray = $q->getResult();
        

                /*
                // This doesnt work, its for old doctrine. the newone only support findBy and findOneBy
        $forklistArray = $this->getDoctrine()
            ->getRepository('ListForksBundle:ForkList')->createQuery('u')
                          ->where('name LIKE ?', '%'.$searchTerm.'%')
                          ->execute();

               */
           




        $resultsArray = array();
        $responseArray = array();

        foreach ( $forklistArray as $forklist)
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
                                                                     'rating' => $forklist->getRating(),
                                                                     'items' => $itemsArray,
                                                                     'createdAt' => $forklist->getCreatedAt(),
                                                                     'updatedAt' => $forklist->getUpdatedAt()
                                                                      );


            array_push($resultsArray, $forklistInfo );
    
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


}