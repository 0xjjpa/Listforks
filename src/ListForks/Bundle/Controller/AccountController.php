<?php

namespace ListForks\Bundle\Controller;

use ListForks\Bundle\Entity\Account;
use ListForks\Bundle\Form\Type\AccountType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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
    public function createAction(Request $request)
    {
        $account = new Account();

        $form = $this->createForm(new AccountType(), $account);

            if( $request->getMethod() == 'POST' )
            {
                $form->bindRequest($request);

                if( $form->isValid() )
                {
                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($account);

                    $password = $encoder->encodePassword($account->getPassword(), $account->getSalt());
                    $account->setPassword($password);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($account);
                    $em->flush();

                    return $this->redirect($this->generateUrl('_login'));
                }
            }

        return $this->render('ListForksBundle:Account:create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/delete", name="_account_delete")
     * @Template()
     */
    public function deleteAction()
    {
        $request = $this->get('request');

        // we dont allow delete through get method
        if ($request->getMethod() == 'POST') {
      



            // Need to do something with the data here
        }

        return $this->render('ListForksBundle:Account:index.html.twig');
    }

    /**
     * @Route("/edit", name="_account_edit")
     * @Template()
     */
    public function editAction()
    {

        $request = $this->get('request');

        // when edit form is submited via post
        if ($request->getMethod() == 'POST') {
      



            // Need to do something with the data here
        }

        // when requesting to get the edit from
        if ($request->getMethod() == 'GET') {
      

            return $this->render('ListForksBundle:Account:edit.html.twig');

            // Need to do something with the data here
        }
        
    }

}
