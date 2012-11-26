<?php

namespace ListForks\Bundle\Controller;

use ListForks\Bundle\Entity\Account;
use ListForks\Bundle\Entity\User;
use ListForks\Bundle\Entity\Preference;

use ListForks\Bundle\Form\Type\AccountType;
use ListForks\Bundle\Form\Type\UserType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

// imports the "@Secure" annotation
use JMS\SecurityExtraBundle\Annotation\Secure;

class AccountsController extends Controller
{
	/**
     * @Secure(roles="ROLE_USER")
     */
	public function optionsAccountsAction()
    {
        return new Response('[OPTIONS] /accounts');

    } // "options_accounts" [OPTIONS] /accounts

    /**
     * @Secure(roles="ROLE_USER")
     *
     * accepts a GET request for the current user's account information
     * returns a JSON response containing the user's information
     *
     * @author Raymond Chow
     *
     * @param
     * @return Response 
     *
     */
    public function getAccountsAction()
    {
        // Get current User
        $account = $this->get('security.context')->getToken()->getUser();

        // Retrieve user from DB
        $user = $this->getDoctrine()
            ->getRepository('ListForksBundle:User')
            ->findOneByAccount($account);

        if( !$user )
        {
            $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'We were unable to retrieve your account information.')));
            // 404: Not Found
            $response->setStatusCode(404);
        }
        else
        {

            $preferences = $user->getPreferences();

            // Array to store preference info
            $preferenceInfo = array();

            foreach( $preferences as $pref )
            {
                // Add preference to array
                $preferenceInfo[] = array( 'id' => $pref->getId(),
                                           'name' => $pref->getName(),
                                           'description' => $pref->getDescription(),
                                           'flag' => $pref->getFlag() );

            }

            // Array to store account info
            $accountInfo = array('accountId' => $account->getId(),
                                 'username' => $account->getUsername(),
                                 'email' => $account->getEmail());

            // Array to store user info
            $userInfo = array('userId' => $user->getId(),
                              'firstName' => $user->getFirstName(),
                              'lastName' => $user->getLastName(),
                              'location' => $user->getLocation());

            // Create a JSON-response
            $response = new Response(json_encode(array(
                            '_hasData' => true,
                            'account' => $accountInfo,
                            'user' => $userInfo,
                            'preferences' => $preferenceInfo)));



            /* 

            // Create a JSON-response
            $response = new Response(json_encode(array(
                'accountId' => $account->getId(),
                'userId' => $user->getId(),
                'username' => $account->getUsername(),
                'email' => $account->getEmail())));    

            */
        }
        
        // Set response header
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    } // "get_accounts"     [GET] /accounts


    /**
     * Account Creation Form
     *
     * @return View
     *
     */
    public function newAccountsAction()
    {

        $form = $this->createForm(new AccountType(), $account = null);

        return $this->render('ListForksBundle:Account:create.html.twig', array('form' => $form->createView()));

    } // "new_accounts"     [GET] /accounts/new

    /**
     * accepts a POST request for a new account
     * returns a JSON response containing the new account information
     *
     * @author Raymond Chow
     *
     * @param
     * @return Response   
     *
     */
    public function postAccountsNewAction()
    {
        // Get current request
        $request = $this->getRequest();

        // Create a new form
        $form = $this->createForm(new AccountType(), $account = null);

        // Bind current request to form
        $form->bindRequest($request);

            // Check if form is valid
            if( $form->isValid() )
            {
                // Retrieve user submitted data
                $account = $form->getData();

                // Retrieve encoder for Account type
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($account);

                // Encode password
                $password = $encoder->encodePassword($account->getPassword(), $account->getSalt());
                $account->setPassword($password);

                // Create a new user and associate it with account
                $user = new User();
                $user->setAccount($account);

                // Create default user preferences

                // Attach user location to lists
                $prefLocation = new Preference();
                $prefLocation->setName("attachLocation");
                $prefLocation->setDescription("Attach your current location to new lists that you create.");
                $prefLocation->setFlag(false);
                $prefLocation->setUser($user);

                // Notify user by e-mail when a list that they are subscribed to is updated
                $prefNotifyEmail = new Preference();
                $prefNotifyEmail->setName("notifyEmail");
                $prefNotifyEmail->setDescription("I would like to receive e-mail notifications for subscribed list updates.");
                $prefNotifyEmail->setFlag(false);
                $prefNotifyEmail->setUser($user);

                // Associate preferences with user
                $user->addPreference($prefLocation);
                $user->addPreference($prefNotifyEmail);

                // Persist account and user to DB
                $em = $this->getDoctrine()->getManager();
                $em->persist($account);
                $em->persist($user);
                $em->flush();

                // Redirect to login page
                return $this->redirect($this->generateUrl('_login'));
            }
            // Form invalid
            else
            {
                $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'We could not create your account.')));

                // 400: Bad Request
                $response->setStatusCode(400);

                // Set response header
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }

    } // "post_accounts_new" [POST] /accounts/new


    /**
     * @Secure(roles="ROLE_USER")
     *
     * accepts a GET request for a specific account's (id) information
     * returns a JSON response containing the account information
     *
     * @author Raymond Chow
     *
     * @param  $id  the account id
     * @return Response 
     *
     */
    public function getAccountAction($id)
    {
        // Get current User
        $account = $this->get('security.context')->getToken()->getUser();

        // Check if requested account ID is the same as the current user's account ID
        if( $account->getId() == $id )
        {
            // Retrieve user from DB
            $user = $this->getDoctrine()
            ->getRepository('ListForksBundle:User')
            ->findOneByAccount($account);

            if( !$user )
            {
                $response = new Response(
                    json_encode(array('_hasData' => false,
                                      'message' => 'We were unable to retrieve your account information.')));
                // 404: Not Found
                $response->setStatusCode(404);
            }
            else
            {

                $preferences = $user->getPreferences();

                // Array to store preference info
                $preferenceInfo = array();

                foreach( $preferences as $pref )
                {
                    // Add preference to array
                    $preferenceInfo[] = array( 'id' => $pref->getId(),
                                               'name' => $pref->getName(),
                                               'description' => $pref->getDescription(),
                                               'flag' => $pref->getFlag() );

                }

                // Array to store account info
                $accountInfo = array('accountId' => $account->getId(),
                                     'username' => $account->getUsername(),
                                     'email' => $account->getEmail());

                // Array to store user info
                $userInfo = array('userId' => $user->getId(),
                                  'firstName' => $user->getFirstName(),
                                  'lastName' => $user->getLastName(),
                                  'location' => $user->getLocation());

                // Create a JSON-response
                $response = new Response(json_encode(array(
                            '_hasData' => true,
                            'account' => $accountInfo,
                            'user' => $userInfo,
                            'preferences' => $preferenceInfo)));
            }


        }
        else
        {
            $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'Unauthorized Access')));

            // 403: Forbidden
            $response->setStatusCode(403);
        }

        // Set response header
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    } // "get_account"      [GET] /accounts/{id}


    /**
     * @Secure(roles="ROLE_USER")
     */
    public function editAccountAction($id)
    {
        return new Response('[GET] /accounts/'.$id.'/edit');

    } // "edit_account"     [GET] /accounts/{id}/edit


    /**
     * @Secure(roles="ROLE_USER")
     *
     * accepts a PUT request for updating a specific account's (id) information
     * returns a JSON response containing the updated account information
     *
     * @author Raymond Chow
     *
     * @param  $id  the account id
     * @return Response 
     *
     */
    public function putAccountAction($id)
    {
        // Get current User
        $account = $this->get('security.context')->getToken()->getUser();

        // Check if requested account ID is the same as the current user's account ID
        if( $account->getId() == $id )
        {
            // Get current request
            $request = $this->getRequest();
            // Get content associated with request
            $content = $request->getContent();

            // Check if content is empty
            if( !empty($content) )
            {
                // Empty array to store the request content
                $updateArray = array();

                // Convert JSON Request Object into an array 
                $updateArray = json_decode($content, true);

                // Get specific info from request
                $updateId = $updateArray['accountId'];
                $updateEmail = $updateArray['email'];
                $updateFirst = $updateArray['firstName'];
                $updateLast = $updateArray['lastName'];
                $updateLocation = $updateArray['location'];
                $updatePreferences = $updateArray['preferences'];

                // Sanitize and validate user input
                $filterId = filter_var( $updateId, FILTER_SANITIZE_NUMBER_INT );
                $filterEmail = filter_var( $updateEmail, FILTER_SANITIZE_EMAIL );
                $validateEmail = filter_var( $filterEmail, FILTER_VALIDATE_EMAIL );
                $filterFirst = filter_var( $updateFirst, FILTER_SANITIZE_STRING );
                $filterLast = filter_var( $updateLast, FILTER_SANITIZE_STRING );
                $filterLocation = filter_var( $updateLocation, FILTER_SANITIZE_STRING );

                // Invalid e-mail
                if( !$validateEmail )
                {
                    // Create a JSON response
                    $response = new Response(
                        json_encode(array('_hasData' => false,
                                      'message' => 'We could not update your account. Invalid e-mail: '.$filterEmail)));
                    // 400: Bad Request
                    $response->setStatusCode(400);
                    $response->headers->set('Content-Type', 'application/json');

                    return $response;
                }

                // Ensure request account id is the same as the current user's account id
                if( $account->getId() == $filterId )
                {
                    // Retrieve user from DB
                    $user = $this->getDoctrine()
                        ->getRepository('ListForksBundle:User')
                        ->findOneByAccount($account);

                    // User not found
                    if( !$user )
                    {
                        // Create a JSON response
                        $response = new Response(
                            json_encode(array('_hasData' => false,
                                              'message' => 'We could not update your account.')));
                        // 404: Not Found
                        $response->setStatusCode(404);
                    }
                    else
                    {
                        // Get current account and user info
                        $email = $account->getEmail();
                        $firstName = $user->getFirstName();
                        $lastName = $user->getLastName();
                        $location = $user->getLocation();
                        $preferences = $user->getPreferences();

                        // Check to see if any information needs to be updated
                        if( $email != $filterEmail )
                        {
                            $account->setEmail($filterEmail);
                            $email = $account->getEmail();
                        }

                        if( $firstName != $filterFirst )
                        {
                            $user->setFirstName($filterFirst);
                            $firstName = $user->getFirstName();
                        }

                        if( $lastName != $filterLast )
                        {
                            $user->setLastName($filterLast);
                            $lastName = $user->getLastName();
                        }

                        if( $location != $filterLocation )
                        {
                            $user->setLocation($filterLocation);
                            $location = $user->getLocation();
                        }

                        foreach( $updatePreferences as $updatePreference )
                        {
                            // Get preference update information
                            $updatePreferenceId = $updatePreference['id'];
                            $updatePreferenceName = $updatePreference['name'];
                            $updatePreferenceFlag = $updatePreference['flag'];

                            // Sanitize input
                            $filterUpdatePrefId = filter_var( $updatePreferenceId, FILTER_SANITIZE_NUMBER_INT );
                            $filterUpdatePrefName = filter_var( $updatePreferenceName, FILTER_SANITIZE_STRING );

                            // Find user preference in DB
                            $preference = $this->getDoctrine()
                                ->getRepository('ListForksBundle:Preference')
                                ->find($filterUpdatePrefId);

                            // Preference not found
                            if( !$preference )
                            {
                                // Create a JSON response
                                $response = new Response(
                                    json_encode(array('_hasData' => false,
                                                      'message' => 'We could not update the preferences for your account.')));
                                // 404: Not Found
                                $response->setStatusCode(404);
                                $response->headers->set('Content-Type', 'application/json');

                                return $response;
                            }
                            // Preference found
                            else
                            {
                                // Check to ensure preference name matches
                                if( $preference->getName() == $filterUpdatePrefName )
                                {
                                    // Check if update flag is a boolean
                                    if( is_bool($updatePreferenceFlag) )
                                    {
                                        // Check to see if preference needs to be updated
                                        if( $updatePreferenceFlag != $preference->getFlag() )
                                        {
                                            $preference->setFlag($updatePreferenceFlag);
                                        }
                                    }
                                }
                                else
                                // Preference name mismatch
                                {
                                    // Create a JSON response
                                    $response = new Response(
                                        json_encode(array('_hasData' => false,
                                                          'message' => 'We could not update the preferences for your account.')));
                                    // 400: Bad Request
                                    $response->setStatusCode(400);
                                    $response->headers->set('Content-Type', 'application/json');

                                    return $response;
                                }

                            }
                        }

                        // Persist changes to DB
                        $em = $this->getDoctrine()->getManager();
                        $em->flush();

                        // Array to store preference info
                        $preferenceInfo = array();

                        foreach( $preferences as $pref )
                        {
                            // Add preference to array
                            $preferenceInfo[] = array( 'id' => $pref->getId(),
                                                       'name' => $pref->getName(),
                                                       'description' => $pref->getDescription(),
                                                       'flag' => $pref->getFlag() );

                        }

                        // Array to store account info
                        $accountInfo = array('accountId' => $account->getId(),
                                              'username' => $account->getUsername(),
                                              'email' => $email);

                        // Array to store user info
                        $userInfo = array('userId' => $user->getId(),
                                          'firstName' => $firstName,
                                          'lastName' => $lastName,
                                          'location' => $location);

                        // Create a JSON-response
                        $response = new Response(json_encode(array(
                            '_hasData' => true,
                            'account' => $accountInfo,
                            'user' => $userInfo,
                            'preferences' => $preferenceInfo)));
                    }
                }
                // Account id does not match
                else
                {
                    // Create a JSON response
                    $response = new Response(
                        json_encode(array('_hasData' => false,
                                          'message' => 'We could not update your account.')));
                    // 400: Bad Request
                    $response->setStatusCode(400);
                }
            }
            // Content is empty
            else
            {
                // Create a JSON response
                $response = new Response(
                    json_encode(array('_hasData' => false,
                                      'message' => 'We could not update your account.')));
                // 400: Bad Request
                $response->setStatusCode(400);
            }  
        }
        // Account id mismatch
        else
        {
            // Create a JSON response
            $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'Unauthorized Access')));

            // 403: Forbidden
            $response->setStatusCode(403);
        }

        // Set response header
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    } // "put_account"      [PUT] /accounts/{id}


    /**
     * @Secure(roles="ROLE_USER")
     *
     * accepts a DELETE request for a specific account (id)
     *
     * @author Raymond Chow
     *
     * @param  $id  the account id
     *
     */
    public function deleteAccountAction($id)
    {
        // Get current User
        $account = $this->get('security.context')->getToken()->getUser();

        // Check if requested account ID is the same as the current user's account ID
        if( $account->getId() == $id )
        {

            // Delete user's account
            $em = $this->getDoctrine()->getManager();
            $em->remove($account);
            $em->flush();

            // Redirect to login page
            return $this->redirect($this->generateUrl('_logout'));
        }
        // Account id mismatch
        else
        {
            // Create a JSON response
            $response = new Response(
                json_encode(array('_hasData' => false,
                                  'message' => 'Unauthorized Access')));

            // 403: Forbidden
            $response->setStatusCode(403);
        }

        // Set response header
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    } // "delete_account"   [DELETE] /accounts/{id}

}