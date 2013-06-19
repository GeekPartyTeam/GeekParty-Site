<?php

namespace Geek\PartyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WorkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('name')
            ->add('description')
            ->add('source')
            ->add('width')
            ->add('height')
            ->add('party', 'entity', [
                    'class' => 'GeekPartyBundle:Party',
                    'property' => 'id'
                ])
            ->add('authors', 'collection', [
                    'type'         => new WorkAuthorType(),
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ])
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
