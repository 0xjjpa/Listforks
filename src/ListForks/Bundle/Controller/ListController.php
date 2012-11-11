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
     * @Route("/", name="_list_index")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('ListForksBundle:List:index.html.twig');
    }

    /**
     * @Route("/create", name="_list_create")
     * @Template()
     */
    public function createAction()
    {

        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
      



            // Need to do something with the data here
        }

        if ($request->getMethod() == 'GET') {
      

            return $this->render('ListForksBundle:List:create.html.twig');

            // Need to do something with the data here
        }

        
    }

    /**
     * @Route("/delete", name="_list_delete")
     * @Template()
     */
    public function deleteAction()
    {
        $request = $this->get('request');
        
        // we dont allow delete via get
        if ($request->getMethod() == 'POST') {
      



            // Need to do something with the data here
        }

        return $this->render('ListForksBundle:List:index.html.twig');
    }

    /**
     * @Route("/edit", name="_list_edit")
     * @Template()
     */
    public function editAction()
    {
        $request = $this->get('request');

        // when edit from is submited via post
        if ($request->getMethod() == 'POST') {
      



            // Need to do something with the data here
        }

        // returns the edit form
        if ($request->getMethod() == 'GET') {
      

            return $this->render('ListForksBundle:List:edit.html.twig');

            // Need to do something with the data here
        }
    }
}
