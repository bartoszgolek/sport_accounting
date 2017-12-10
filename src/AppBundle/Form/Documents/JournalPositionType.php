<?php

namespace AppBundle\Form\Documents;

use AppBundle\Entity\Booking\Book;
use AppBundle\Entity\Documents\JournalPosition;
use AppBundle\Form\Type\DatePickerType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ->add('date', DatePickerType::class)
            ->add('book', EntityType::class, [
                'class' => Book::class,
                'query_builder' => function(EntityRepository $er) use($options) {
                    return $er->createQueryBuilder('b')->orderBy('b.description', 'ASC');
                },
                'choice_label' => 'description',
                'expanded' => false,
                'multiple' => false,
                'placeholder' => ""
            ])
            ->add('description')
            ->add('debit', NumberType::class, [
                'scale' => 2,
                'required' => false,
            ])
            ->add('credit', NumberType::class, [
                'scale' => 2,
                'required' => false,
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JournalPosition::class
        ]);
    }
}
