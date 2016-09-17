<?php

namespace SRQ\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
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
            ->add('description')
            ->add('user', 'entity', array(
                'class' => 'SRQUserBundle:User',
                'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->where('u.role = :only')
                            ->setParameter('only', 'ROLE_USER');
                    },
                    'choice_label' => 'getFullName'
                ))
            ->add('save', 'submit', array('label' => 'Save Task'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
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
         return 'task';
     }
}
