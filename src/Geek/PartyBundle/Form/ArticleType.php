<?php

namespace Geek\PartyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('body', 'textarea', [
                'attr' => [
                    'class' => 'tinymce',
                    'data-theme' => $options['is_admin'] ? 'admin' : 'advanced',
                ]
            ])
            ->add('poll', 'entity', [
                'class' => 'Prism\PollBundle\Entity\Poll',
                'empty_value' => 'No poll',
                'required' => false,
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Geek\PartyBundle\Entity\Article',
            'is_admin' => false,
        ));
    }

    public function getName()
    {
        return 'geek_partybundle_articletype';
    }
}
