<?php

namespace SRQ\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 
use SRQ\UserBundle\Entity\Task;
use SRQ\UserBundle\Form\TaskType;


class TaskController extends Controller
{
    
    public function indexAction()
    {
        exit('Lista de tareas');
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
}
