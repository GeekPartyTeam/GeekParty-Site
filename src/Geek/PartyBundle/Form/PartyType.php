<?php

namespace Geek\PartyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PartyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('themeSubmissionStartTime')
            ->add('themeSubmissionEndTime')
            ->add('themeVotingStartTime')
            ->add('themeVotingEndTime')
            ->add('startTime')
            ->add('endTime')
            ->add('projectVotingStartTime')
            ->add('projectVotingEndTime')
            ->add('description', 'textarea', [
                 'attr' => [
                     'class' => 'tinymce',
                     'data-theme' => 'advanced'
                 ]
             ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Geek\PartyBundle\Entity\Party'
        ));
    }

    public function getName()
    {
        return 'geek_partybundle_partytype';
    }
}
