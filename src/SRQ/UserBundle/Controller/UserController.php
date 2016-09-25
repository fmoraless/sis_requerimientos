<?php

namespace SRQ\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;
use SRQ\UserBundle\Entity\User;
use SRQ\UserBundle\Form\UserType;




class UserController extends Controller
{
    public function homeAction()
    {
        return $this->render('SRQUserBundle:User:home.html.twig');
    }
    public function indexAction(Request $request)
    {
        $searchQuery = $request->get('query');
        
        if(!empty($searchQuery))
        {
            $finder = $this->container->get('fos_elastica.finder.app.user');
            $users = $finder->createPaginatorAdapter($searchQuery);
        }
        else
        {
            $fm = $this->getDoctrine()->getManager();
            $dql = "SELECT u FROM SRQUserBundle:User u ORDER BY u.id DESC";
            $users = $fm->createQuery($dql); 
        }
        
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
            $users, $request->query->getInt('page', 1),
            5
        );
        
        
        $deleteFormAjax = $this->createCustomForm(':USER_ID', 'DELETE', 'srq_user_delete');
        

        
        return $this->render('SRQUserBundle:User:index.html.twig', array('pagination' => $pagination,
        'delete_form_ajax' => $deleteFormAjax->createView()));
        
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
            
            $passwordConstraint = new Assert\NotBlank();
            $errorList = $this->get('validator')->validate($password, $passwordConstraint);
            
            if(count($errorList) == 0)
            {
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $password);
            
                $user->setPassword($encoded);
            
                $fm = $this->getDoctrine()->getManager();
                $fm->persist($user);
                $fm->flush();
            
                $successMessage = $this->get('translator')->trans('The user has been created.');
                $this->addFlash('mensaje', $successMessage);
            
                return $this->redirectToRoute('srq_user_index');
            }
            else
            {
                $errorMessage = new FormError($errorList[0]->getMessage());
                $form->get('password')->addError($errorMessage);   
            }
        }
        
        return $this->render('SRQUserBundle:User:add.html.twig', array('form' =>$form->createView()));
        
    }
    
    public function editAction($id)
    {
        $fm = $this->getDoctrine()->getManager();
        $user = $fm->getRepository('SRQUserBundle:User')->find($id);
        
        if(!$user)    
        {
            $messageException = $this->get('translator')->trans('User not found.');
            throw $this->createNotFoundException('$messageException');
        }
        
        $form = $this->createEditForm($user);
        
        return $this->render('SRQUserBundle:User:edit.html.twig', array('user' => $user, 'form' => $form->createView()));
    
    }
    
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array('action' => $this->generateUrl('srq_user_update', array('id' => $entity->getId())), 'method' => 'PUT'));
        
        return $form;
    }
    
    public function updateAction($id, Request $request)
    {
        $fm = $this->getDoctrine()->getManager();
        
        $user = $fm->getRepository('SRQUserBundle:User')->find($id);
        
        if(!$user)    
        {
            $messageException = $this->get('translator')->trans('User not found.');
            throw $this->createNotFoundException('$messageException');
        }
        
        $form = $this->createEditForm($user);
        $form ->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $password = $form->get('password')->getData();
            if(!empty($password))
            {
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $password);
                $user->setPassword($encoded);
            }
            else
            {
                $recoverPass = $this->recoverPass($id);
                $user->setPassword($recoverPass[0]['password']);
            }
            
            if($form->get('role')->getData() == 'ROLE_ADMIN')
            {
                $user->setIsActive(1);
            }
            
            $fm->flush();
            
            $successMessage = $this->get('translator')->trans('The user has been modified.');
            $this->addFlash('mensaje', $successMessage);
            return $this->redirectToRoute('srq_user_edit', array('id' => $user->getId()));
        }
        return $this->render('SRQUserBundle:User:edit.html.twig', array('user' => $user, 'form' => $form->createView()));
    }
    
    private function recoverPass($id)
    {
        $fm = $this->getDoctrine()->getManager();
        $query = $fm->createQuery(
            'SELECT u.password
            FROM SRQUserBundle:User u
            WHERE u.id = :id'
            )->setParameter('id', $id);
            
            $currentPass = $query->getResult();
            return $currentPass;
    }
    
    
    public function viewAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('SRQUserBundle:User');
        
        $user = $repository->find($id);
        
        if(!$user)    
        {
            $messageException = $this->get('translator')->trans('User not found.');
            throw $this->createNotFoundException('$messageException');
        }
        
        $deleteForm = $this->createCustomForm($user->getId(), 'DELETE', 'srq_user_delete');
        
        return $this->render('SRQUserBundle:User:view.html.twig', array('user' => $user, 'delete_form' => $deleteForm->createView()));
    }
    
    private function createDeleteForm($user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('srq_user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm();
            
    }
    
    public function deleteAction(Request $request, $id)
    {
        $fm = $this->getDoctrine()->getManager();
        
        $user = $fm->getRepository('SRQUserBundle:User')->find($id);
        
        if(!$user)    
        {
            $messageException = $this->get('translator')->trans('User not found.');
            throw $this->createNotFoundException('$messageException');
        }
        
        
        $allUsers = $fm->getRepository('SRQUserBundle:User')->findAll();
        $countUsers = count($allUsers);
        
        $form = $this->createCustomForm($user->getId(), 'DELETE', 'srq_user_delete');
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            if($request->isXMLHttpRequest())
            {
                $res = $this->deleteUser($user->getRole(), $fm, $user);
                
                return new Response(
                    json_encode(array('removed' => $res['removed'], 'message' => $res['message'], 'countUsers' => $countUsers)),
                    200,
                    array('Content-Type' => 'application/json')
                    );
            }
            
            $res = $this->deleteUser($user->getRole(), $fm, $user);
            
            $this->addFlash($res['alert'], $res['message']);
            return $this->redirectToRoute('srq_user_index');
        }
        
    }
    
    
    private function deleteUser($role, $fm, $user)
    {
        if($role == 'ROLE_USER')
        {
            $fm->remove($user);
            $fm->flush();
            
            $message = $this->get('translator')->trans('The user has been deleted.');
            $removed = 1;
            $alert = 'mensaje';
        }
        elseif($role == 'ROLE_ADMIN')
        {
            $message = $this->get('translator')->trans('The user could not be deleted.');
            $removed = 0;
            $alert = 'error';
        }
        
        return array('removed' => $removed, 'message' => $message, 'alert' => $alert);
    }
    
    private function createCustomForm($id, $method, $route)
    {
        return $this->createFormBuilder()
               ->setAction($this->generateUrl($route, array('id' => $id)))
               ->setMethod($method)
               ->getForm();
    }

}
