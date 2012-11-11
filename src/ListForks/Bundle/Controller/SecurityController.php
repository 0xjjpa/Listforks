<?php

namespace ListForks\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\SecurityContext;
use JMS\SecurityExtraBundle\Annotation\Secure;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SecurityController extends Controller
{
	/**
     * @Route("/account/login", name="_login")
     * @Template()
     */
	public function loginAction()
	{
		$request = $this->getRequest();
		$session = $request->getSession();

		// Authentication handled by Symfony security system

		// login error check
		if( $request->attributes->has(SecurityContext::AUTHENTICATION_ERROR) )
		{
			$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
		}
		else
		{
			$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
			$session->remove(SecurityContext::AUTHENTICATION_ERROR);
		}

		// form submission automatically handled by Symfony security system
		return $this->render('ListForksBundle:Security:login.html.twig', 
			array( 'last_username' => $session->get(SecurityContext::LAST_USERNAME), 'error' => $error ));
	}

	/**
     * @Route("/account/login_check", name="_security_check")
     */
    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/account/logout", name="_logout")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }
}