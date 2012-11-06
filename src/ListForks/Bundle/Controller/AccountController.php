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
        return new Response('Account Index');
    }

    /**
     * @Route("/create", name="_account_create")
     * @Template()
     */
    public function createAction()
    {
        return new Response('Account Create');
    }

    /**
     * @Route("/delete", name="_account_delete")
     * @Template()
     */
    public function deleteAction()
    {
        return new Response('Account Delete');
    }

    /**
     * @Route("/edit", name="_account_edit")
     * @Template()
     */
    public function editAction()
    {
        return new Response('Account Edit');
    }

    /**
     * @Route("/view", name="_account_view")
     * @Template()
     */
    public function viewAction()
    {
        return new Response('Account View');
    }

    /**
     * @Route("/login", name="_account_login")
     * @Template()
     */
    public function loginAction()
    {
        return new Response('Account Login');
    }

    /**
     * @Route("/logout", name="_account_logout")
     * @Template()
     */
    public function logoutAction()
    {
        return new Response('Account Logout');
    }
}
