<?php

namespace Acme\ListForksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SearchController extends Controller
{
    public function indexAction()
    {
        /*
         * The action's view can be rendered using render() method
         * or @Template annotation as demonstrated in ListForksController.
         *
         */
        return $this->render('AcmeListForksBundle:Welcome:index.html.twig');
    }
}
