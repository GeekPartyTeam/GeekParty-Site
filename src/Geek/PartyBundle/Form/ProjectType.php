<?php

namespace Geek\PartyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('name')
            ->add('description', 'textarea', ['attr' => ['cols' => 50, 'rows' => 10]])
            ->add('icon', 'file', ['mapped' => false, 'required' => false])
            ->add('file', 'file', ['mapped' => false, 'required' => false])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Geek\PartyBundle\Entity\Work'
        ));
    }

    public function getName()
    {
        return 'geek_partybundle_worktype';
    }
}
