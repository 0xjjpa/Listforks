<?php

namespace Acme\ListsForkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SearchController extends Controller
{
    public function indexAction()
    {
        /*
         * The action's view can be rendered using render() method
         * or @Template annotation as demonstrated in ListsForkController.
         *
         */
        return $this->render('AcmeListsForkBundle:Welcome:index.html.twig');
    }
}
