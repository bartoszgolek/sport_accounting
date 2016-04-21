<?php

namespace AppBundle\Form\Documents;

use AppBundle\Entity\Documents\Journal;
use AppBundle\Form\Type\JournalTypesType;
use AppBundle\Form\Type\RowsCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description')
            ->add('type', JournalTypesType::class)
            ->add('positions', RowsCollectionType::class, array(
                'entry_type' => JournalPositionType::class,
                'allow_delete' => true,
                'allow_add'    => true,
            ))
            ->add('commit', SubmitType::class)
            ->add('save', SubmitType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Journal::class
        ));
    }
}
