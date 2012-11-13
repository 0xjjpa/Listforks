<?php

namespace ListForks\Bundle\Controller;

use ListForks\Bundle\Entity\Account;
use ListForks\Bundle\Entity\Profile;

use ListForks\Bundle\Form\Type\AccountType;
use ListForks\Bundle\Form\Type\ProfileType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

// imports the "@Secure" annotation
use JMS\SecurityExtraBundle\Annotation\Secure;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AccountController extends Controller
{
	/**
     * @Route("/", name="_account")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction(Request $request)
    {
        $account = $this->get('security.context')->getToken()->getUser();

        $profile = $this->getDoctrine()
            ->getRepository('ListForksBundle:Profile')
            ->findOneByAccount($account);

        $form = $this->createForm(new ProfileType(), $profile);

            if( $request->getMethod() == 'POST' )
            {
                $this->forward('ListForksBundle:Account:edit', array('form' => $form));
            }

        return $this->render('ListForksBundle:Account:index.html.twig', array('form' => $form->createView()));
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

                    $profile = new Profile();
                    $profile->setAccount($account);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($account);
                    $em->persist($profile);
                    $em->flush();

                    return $this->redirect($this->generateUrl('_login'));
                }
            }

        return $this->render('ListForksBundle:Account:create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/delete", name="_account_delete")
     * @Template()
     * @Secure(roles="ROLE_USER")
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
     * @Secure(roles="ROLE_USER")
     */
    public function editAction(Request $request, Form $form)
    {

        $request = $this->get('request');

        // when edit form is submited via post
        if( $request->getMethod() == 'POST' )
        {
            $form->bindRequest($request);

            if( $form->isValid() )
            {
                $profile = $form->getData();

                $account = $profile->getAccount();

                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($account);

                $account->setSalt( md5(uniqid(null, true)) );

                $password = $encoder->encodePassword($account->getPassword(), $account->getSalt());
                $account->setPassword($password);

                $em = $this->getDoctrine()->getManager();
                $em->flush();

                return $this->redirect($this->generateUrl('_account'));
            }
        }

        // when requesting to get the edit from
        if ($request->getMethod() == 'GET') {
      
            return $this->render('ListForksBundle:Account:index.html.twig', array('form' => $form->createView()));

            // Need to do something with the data here
        }
        
    }

}
