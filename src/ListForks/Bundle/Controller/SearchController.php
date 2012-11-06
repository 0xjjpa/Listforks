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
        return new Response('Search Index');
    }

    /**
     * @Route("/keyword", name="_search_keyword")
     * @Template()
     */
    public function keywordAction()
    {
        return new Response('Search By Keyword');
    }

    /**
     * @Route("/location", name="_search_location")
     * @Template()
     */
    public function locationAction()
    {
        return new Response('Search By Location');
    }

    /**
     * @Route("/tag", name="_search_tag")
     * @Template()
     */
    public function tagAction()
    {
        return new Response('Search By Tag');
    }
}
