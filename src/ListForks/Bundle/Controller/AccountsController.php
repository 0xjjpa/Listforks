<?php

namespace ListForks\Bundle\Controller;

use ListForks\Bundle\Entity\Account;
use ListForks\Bundle\Entity\User;

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
	
	public function optionsAccountsAction()
    {
        return new Response('[OPTIONS] /accounts');

    } // "options_accounts" [OPTIONS] /accounts


    /**
     * @Secure(roles="ROLE_USER")
     */
    public function getAccountsAction()
    {
        return new Response('[GET] /accounts');

    } // "get_accounts"     [GET] /accounts


    public function newAccountsAction()
    {

        $form = $this->createForm(new AccountType(), $account = null);

        return $this->render('ListForksBundle:Account:create.html.twig', array('form' => $form->createView()));

    } // "new_accounts"     [GET] /accounts/new


    public function postAccountsAction()
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

                // Persist account and user to DB
                $em = $this->getDoctrine()->getManager();
                $em->persist($account);
                $em->persist($user);
                $em->flush();

                // Redirect to login page
                return $this->redirect($this->generateUrl('_login'));
            }
            else
            {
                return new Response('[POST] /accounts');
            }

    } // "post_accounts"    [POST] /accounts


    /**
     * @Secure(roles="ROLE_USER")
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

            // Create a JSON-response
            $response = new Response(json_encode(array(
                'account_id' => $account->getId(),
                'username' => $account->getUsername(),
                'email' => $account->getEmail())));

            // Set response header
            $response->headers->set('Content-Type', 'application/json');
        }
        else
        {
            $response = new Response('Unauthorized Access');
        }

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
     */
    public function putAccountAction($id)
    {
        return new Response('[PUT] /accounts/'.$id);

    } // "put_account"      [PUT] /accounts/{id}


    /**
     * @Secure(roles="ROLE_USER")
     */
    public function deleteAccountAction($id)
    {
        return new Response('[DELETE] /accounts/'.$id);

    } // "delete_account"   [DELETE] /accounts/{id}

}