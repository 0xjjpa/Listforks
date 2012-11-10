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
        return $this->render('ListForksBundle:Account:index.html.twig');
    }

    /**
     * @Route("/delete", name="_account_delete")
     * @Template()
     */
    public function deleteAction()
    {
        return $this->render('ListForksBundle:Account:index.html.twig');
    }

    /**
     * @Route("/edit", name="_account_edit")
     * @Template()
     */
    public function editAction()
    {
        return $this->render('ListForksBundle:Account:index.html.twig');
    }

    /**
     * @Route("/view", name="_account_view")
     * @Template()
     */
    public function viewAction()
    {
        return $this->render('ListForksBundle:Account:index.html.twig');
    }

    /**
     * @Route("/login", name="_account_login")
     * @Template()
     */
    public function loginAction()
    {
        return $this->render('ListForksBundle:Account:index.html.twig');
    }

    /**
     * @Route("/logout", name="_account_logout")
     * @Template()
     */
    public function logoutAction()
    {
        return $this->render('ListForksBundle:Account:index.html.twig');
    }
}
