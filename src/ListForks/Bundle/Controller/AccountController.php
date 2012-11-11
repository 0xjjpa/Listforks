<?php

namespace ListForks\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AccountController extends Controller
{
	/**
     * @Route("/", name="_account")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('ListForksBundle:Account:index.html.twig');
    }

    /**
     * @Route("/create", name="_account_create")
     * @Template()
     */
    public function createAction()
    {
        $request = $this->get('request');

        // when create form is submited via post
        if ($request->getMethod() == 'POST') {
      



            // Need to do something with the data here
        }

        // when requesting to get the create from
        if ($request->getMethod() == 'GET') {
      

            return $this->render('ListForksBundle:Account:create.html.twig');

            // Need to do something with the data here
        }
        
    }

    /**
     * @Route("/delete", name="_account_delete")
     * @Template()
     */
    public function deleteAction()
    {
        $request = $this->get('request');

        // we dont allow delete through get method
        if ($request->getMethod() == 'POST') {
      



            // Need to do something with the data here
        }

        return $this->render('ListForksBundle:Account:index.html.twig');
    }

    /**
     * @Route("/edit", name="_account_edit")
     * @Template()
     */
    public function editAction()
    {

        $request = $this->get('request');

        // when edit form is submited via post
        if ($request->getMethod() == 'POST') {
      



            // Need to do something with the data here
        }

        // when requesting to get the edit from
        if ($request->getMethod() == 'GET') {
      

            return $this->render('ListForksBundle:Account:edit.html.twig');

            // Need to do something with the data here
        }
        
    }

    /**
     * @Route("/login", name="_account_login")
     * @Template()
     */
    public function loginAction()
    {
        $request = $this->get('request');

        // when login form is submited via post
        if ($request->getMethod() == 'POST') {
      



            // Need to do something with the data here

            // after login forward to the first page
            return $this->redirect("../list");
        }

        // when requesting to get the login from
        if ($request->getMethod() == 'GET') {
      

            // get the login page. if we decide to show 
            // this on the first page then this can return the same view or can be a redirect
            return $this->render('ListForksBundle:Account:login.html.twig');

            // Need to do something with the data here
        }
        
    }

    /**
     * @Route("/logout", name="_account_logout")
     * @Template()
     */
    public function logoutAction()
    {
        $request = $this->get('request');

        // when log out form is submited via post
        if ($request->getMethod() == 'POST') {
      


            // after logout redirect to the first page
            return $this->redirect("../list");


            // Need to do something with the data here
        }

        return $this->redirect("../list");

        
    }
}
