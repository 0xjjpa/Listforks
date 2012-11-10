<?php

namespace ListForks\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SearchController extends Controller
{
	/**
     * @Route("/", name="_search")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('ListForksBundle:Search:index.html.twig');
    }



    /**
     * @Route("/search", name="_search_results")
     * @Template()
     */
    public function searchAction()
    {
        return $this->render('ListForksBundle:Search:results.html.twig');
    }


    /**
     * @Route("/keyword", name="_search_keyword")
     * @Template()
     */
    public function keywordAction()
    {
        return $this->render('ListForksBundle:Search:results.html.twig');
    }

    /**
     * @Route("/location", name="_search_location")
     * @Template()
     */
    public function locationAction()
    {
        return $this->render('ListForksBundle:Search:results.html.twig');
    }

    /**
     * @Route("/tag", name="_search_tag")
     * @Template()
     */
    public function tagAction()
    {
        return $this->render('ListForksBundle:Search:results.html.twig');
    }
}
