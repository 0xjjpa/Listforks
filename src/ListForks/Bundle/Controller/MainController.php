<?php

namespace ListForks\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MainController extends Controller
{
	/**
     * @Route("/", name="_main")
     * @Template()
     */
    public function indexAction()
    {
        $request = $this->get('request');

        // when search form is submited via post
        if ($request->getMethod() == 'POST') {
      



            // Need to do something with the data here
        }

        // when requesting to get the search page
        if ($request->getMethod() == 'GET') {
      

            return $this->render('ListForksBundle:Main:index.html.twig');

            // Need to do something with the data here
        }

        return $this->render('ListForksBundle:Main:index.html.twig');        
    }

}
