<?php

namespace Geek\PartyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PartyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $addDateTimeWidget = function ($name) use ($builder) {
            $builder->add($name, 'datetime', [
                'widget'=> 'single_text',
                'format'=>'yyyy-MM-dd HH:mm:SS',
                'attr' => [
                    'class' => 'datetime'
                ]
            ]);
        };

        $builder->add('id');

        $addDateTimeWidget('themeSubmissionStartTime');
        $addDateTimeWidget('themeSubmissionEndTime');
        $addDateTimeWidget('themeVotingStartTime');
        $addDateTimeWidget('themeVotingEndTime');
        $addDateTimeWidget('startTime');
        $addDateTimeWidget('endTime');
        $addDateTimeWidget('projectVotingStartTime');
        $addDateTimeWidget('projectVotingEndTime');

        $builder->add('description', 'textarea', [
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
