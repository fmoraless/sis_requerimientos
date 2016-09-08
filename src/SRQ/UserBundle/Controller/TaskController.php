<?php

namespace SRQ\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 
use EMM\UserBundle\Entity\Task;
use EMM\UserBundle\Form\TaskType;

class TaskController extends Controller
{
    
    public function addAction()
    {
        $task = new Task();
        $form = $this->createCreateForm($task);
        
        return $this->render('SRQUserBundle:Task:add.html.twig', array('form' => createView()));
    }
    
    private function createCreateForm(Task $entity)
    {
        $form = $this->createForm(new TaskType(), $entity, array(
                'action' => $this->generateUrl('srq_task_create'),
                'mehod' => 'POST'
            ));
            return $form;
    }
}
