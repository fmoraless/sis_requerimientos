<?php

namespace SRQ\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 
use SRQ\UserBundle\Entity\Task;
use SRQ\UserBundle\Form\TaskType;


class TaskController extends Controller
{
    
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT t FROM SRQUserBundle:Task t ORDER BY t.id DESC";
        $tasks = $em->createQuery($dql);
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $tasks,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('SRQUserBundle:Task:index.html.twig', array('pagination' => $pagination));
    }
    
    public function addAction()
    {
        $task = new Task();
        $form = $this->createCreateForm($task);
        
        return $this->render('SRQUserBundle:Task:add.html.twig', array('form' => $form->createView()));
    }
    
    private function createCreateForm(Task $entity)
    {
        $form = $this->createForm(new TaskType(), $entity, array(
                'action' => $this->generateUrl('srq_task_create'),
                'method' => 'POST'
            ));
            
            return $form;
    }
    
    public function createAction(Request $request)
    {
        $task = new Task();
        $form = $this->createCreateForm($task);
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $task->setStatus(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            
            $this->addFlash('mensaje', 'The task has been created.');
            return $this->redirectToRoute('srq_task_index');
        }
        
        return $this->render('SRQUserBundle:Task:add.html.twig', array('form' => $form->createView()));
    }
    
 
    public function viewAction($id)
    {
        $task = $this->getDoctrine()->getRepository('SRQUserBundle:Task')->find($id);
        if(!$task)            
        {
            throw $this->createNotFoundException('The task does not exist.');    
        }
        
        $deleteForm = $this->createCustomForm($task->getId(), 'DELETE', 'srq_task_delete');
        $user = $task->getUser();
        
        return $this->render('SRQUserBundle:Task:view.html.twig',array('task' => $task, 'user' => $user,
            'delete_form' => $deleteForm->createView()));
    }
    
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $task = $em->getRepository('SRQUserBundle:Task')->find($id);
        
        if(!$task)
        {
            throw $this->createNotFoundException('The task not found.');
        }
        
        $form = $this->createEditForm($task);
        
        return $this->render('SRQUserBundle:Task:edit.html.twig', array('task' => $task, 'form' => $form
              ->createView()));
    }
    
    private function createEditForm(Task $entity)
    {
        $form = $this->createForm(new TaskType(), $entity, array(
            'action' => $this->generateUrl('srq_task_update', array('id' => $entity->getId())),
            'method' => 'PUT'
            ));
            
            return $form;
    }
    
    Public function updateAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $task = $em->getRepository('SRQUserBundle:Task')->find($id);
        
        if(!$task)
        {
            throw $this->createNotFoundException('The task not found.');
        }
        $form = $this->createEditForm($task);
        $form->handleRequest($request);
        
        if($form->isSubmitted() and $form->isValid())
        {
            $task->setStatus(0);
            $em->flush();
            $this->addFlash('mensaje', 'The taks has been modified');
            return $this->redirectToRoute('srq_task_edit', array('id' => $task->getId()));
        }
        return $this->render('SRQUserBundle:Task:edit.html.twig', array('task' => $task, 'form' => $form
            ->createView()));
    }
    
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('SRQUserBundle:Task')->find($id);
        
        if(!$task)
        {
            throw $this->createNotFoundException('The task not found.');
        }
        
        $form = $this->createCustomForm($task->getId(), 'DELETE', 'srq_task_delete');
        $form->handleRequest($request);
        
        if($form->isSubmitted() and $form->isValid())
        {
            $em->remove($task);
            $em->flush();
            
            $this->addFlash('mensaje', 'The Task has been deleted');
            
            return $this->redirectToRoute('srq_task_index');
        }
    }
    
    private function createCustomForm($id, $method, $route)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($route, array('id' => $id)))
            ->setMethod($method)
            ->getForm();
    }
    
}

