<?php

namespace ListForks\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ListController extends Controller
{
	/**
     * @Route("/", name="_default")
     * @Template()
     */
    public function indexAction()
    {
        return new Response('List Index');
    }

    /**
     * @Route("/create", name="_list_create")
     * @Template()
     */
    public function createAction()
    {
        return new Response('List Create');
    }

    /**
     * @Route("/delete", name="_list_delete")
     * @Template()
     */
    public function deleteAction()
    {
        return new Response('List Delete');
    }

    /**
     * @Route("/edit", name="_list_edit")
     * @Template()
     */
    public function editAction()
    {
        return new Response('List Edit');
    }

    /**
     * @Route("/view", name="_list_view")
     * @Template()
     */
    public function viewAction()
    {
        return new Response('List View');
    }
}
