<?php

namespace SRQ\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;


class TaskType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('descripcion')
            ->add('user', 'entity', array(
                'class' => 'SRQUserBundle:User',
                'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->where('u.role = :only')
                            ->setParameter('only', 'ROLE_USER');
                    },
                    'choise_label' => 'getFullName'
                ))
            ->add('save', 'submit', array('label' => 'Save Task'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SRQ\UserBundle\Entity\Task'
        ));
    }
    
    /**
     * @return string
     */
     public function getName()
     {
         return 'srq_userbundle_task';
     }
}
