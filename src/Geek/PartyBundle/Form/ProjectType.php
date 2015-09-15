<?php

namespace Geek\PartyBundle\Form;

use Geek\PartyBundle\Entity\Work;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden')
            ->add('name')
            ->add('description', 'textarea', ['attr' => ['cols' => 50, 'rows' => 10]])
            ->add('longDescription', 'textarea', ['attr' => [
                'class' => 'tinymce',
                'data-theme' => 'advanced'
            ]])
            ->add('icon', 'file', ['mapped' => false, 'required' => false])
            ->add('file', 'file', ['mapped' => false, 'required' => false])
            ->add('windowsBuild')
            ->add('macBuild')
            ->add('linuxBuild')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Work::class,
        ]);
    }

    public function getName()
    {
        return 'geek_partybundle_worktype';
    }
}
