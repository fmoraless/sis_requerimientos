<?php

namespace SRQ\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use SRQ\UserBundle\Entity\User;
use SRQ\UserBundle\Form\UserType;


class UserController extends Controller
{
    public function indexAction()
    {
        $fm = $this->getDoctrine()->getManager();
        
        $users = $fm->getRepository('SRQUserBundle:User')->findAll();
        
        /*
        $res = 'Lista de Usuarios: <br/>';
        
        foreach($users as $user)
        {
            $res .= 'Usuario: ' . $user->getUsername() . ' - Email: ' . $user->getEmail() . '<br/>';    
        }
        
        return new Response($res);
        */
        return $this->render('SRQUserBundle:User:index.html.twig', array('users' => $users));
        
    }
    
    public function addAction()
    {
        $user = new User();
        $form = $this->createCreateForm($user);
        
        return $this->render('SRQUserBundle:User:add.html.twig', array('form' => $form->createView()));
        
    }
    
    
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('srq_user_create'),
            'method' => 'POST'
            ));
        
        return $form;
    }
    
    public function createAction(Request $request)
    {
        $user = new User();
        $form = $this->createCreateForm($user);
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $password = $form->get('password')->getData();
            
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $password);
            
            $user->setPassword($encoded);
            
            $fm = $this->getDoctrine()->getManager();
            $fm->persist($user);
            $fm->flush();
            
            return $this->redirectToRoute('srq_user_index');
        }
        
        return $this->render('SRQUserBundle:User:add.html.twig', array('form' =>createView()));
    }
    
    public function viewAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('SRQUserBundle:User');
        
        $user = $repository->find($id);
        
        return new Response('Usuario: ' . $user->getUsername() . ' con email: ' . $user->getEmail());
    }
}
