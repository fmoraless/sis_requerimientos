<?php

namespace SRQ\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_Utils');
        
        $error = $authenticationUtils->getLastAuthenticationError();
        
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('SRQUserBundle:Security:login.html.twig', array('last_username' => $lastUsername,
            'error' => $error));
    }
    
    public function loginCheckAction()
    {
        
    }
}
