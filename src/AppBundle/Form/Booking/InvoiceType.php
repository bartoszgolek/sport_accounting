<?php

namespace AppBundle\Form\Booking;

use AppBundle\Form\Type\DatePickerType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('invoice_number')
            ->add('invoice_date', DatePickerType::class)
            ->add('amount', NumberType::class, array(
                'scale' => 2
            ))
            ->add('school', EntityType::class, array(
                'class' => 'AppBundle\Entity\Booking\Book',
                'query_builder' => function(EntityRepository $er) use($options) {
                    return $er->createQueryBuilder('b')
                              ->where('b.type = :bookType')
                              ->setParameter('bookType', BookTypes::SCHOOL)
                              ->orderBy('b.description', 'ASC');
                },
                'choice_label' => 'description',
                'expanded' => false,
                'multiple' => false
            ))
            ->add('number_of_reinforcements', IntegerType::class)
            ->add('reinforcements_cost', NumberType::class, array(
                'scale' => 2
            ))
            ->add('reinforcements_book', EntityType::class, array(
                'class' => 'AppBundle\Entity\Booking\Book',
                'query_builder' => function(EntityRepository $er) use($options) {
                    return $er->createQueryBuilder('b')
                              ->where('b.type = :bookType')
                              ->setParameter('bookType', BookTypes::PLAYER)
                              ->orderBy('b.description', 'ASC');
                },
                'choice_label' => 'description',
                'expanded' => false,
                'multiple' => false
            ))
            ->add('players', EntityType::class, array(
                'class' => 'AppBundle\Entity\Booking\Book',
                'query_builder' => function(EntityRepository $er) use($options) {
                    return $er->createQueryBuilder('b')
                              ->where('b.type = :bookType')
                              ->setParameter('bookType', BookTypes::PLAYER)
                              ->orderBy('b.description', 'ASC');
                },
                'choice_label' => 'description',
                'expanded' => true,
                'multiple' => true
            ));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Booking\Invoice'
        ));
    }
}
