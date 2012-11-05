<?php

namespace Acme\ListForksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Acme\ListForksBundle\Form\ContactType;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ListController extends Controller
{
    /**
     * @Route("/", name="_ListForks")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/hello/{name}", name="_ListForks_create")
     * @Template()
     */
    public function createAction($name)
    {
        return array('name' => $name);
    }



    /**
     * @Route("/hello/{name}", name="_ListForks_update")
     * @Template()
     */
    public function updateAction($name)
    {
        return array('name' => $name);
    }

    /**
     * @Route("/contact", name="_ListForks_delete")
     * @Template()
     */
    public function deleteAction()
    {
        $form = $this->get('form.factory')->create(new ContactType());

        $request = $this->get('request');
        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $mailer = $this->get('mailer');
                // .. setup a message and send it
                // http://symfony.com/doc/current/cookbook/email.html

                $this->get('session')->setFlash('notice', 'Message sent!');

                return new RedirectResponse($this->generateUrl('_ListForks'));
            }
        }

        return array('form' => $form->createView());
    }
}
