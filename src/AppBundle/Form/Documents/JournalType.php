<?php

namespace AppBundle\Form\Documents;

use AppBundle\Form\Type\JournalTypesType;
use AppBundle\Form\Type\RowsCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
                'allow_add' => true,
                'allow_delete' => true
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Documents\Journal'
        ));
    }
}
