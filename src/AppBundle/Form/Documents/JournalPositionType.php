<?php

namespace AppBundle\Form\Documents;

use AppBundle\Entity\Documents\JournalPosition;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Finder\Comparator\NumberComparator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalPositionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('voucher')
            ->add('book', EntityType::class, array(
                'class' => 'AppBundle\Entity\Booking\Book',
                'query_builder' => function(EntityRepository $er) use($options) {
                    return $er->createQueryBuilder('b')->orderBy('b.description', 'ASC');
                },
                'choice_label' => 'description',
                'expanded' => false,
                'multiple' => false
            ))
            ->add('description')
            ->add('debit', NumberType::class, array(
                'scale' => 2
            ))
            ->add('credit', NumberType::class, array(
                'scale' => 2
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Documents\JournalPosition'
        ));
    }
}
