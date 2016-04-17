<?php

namespace AppBundle\Form;

use AppBundle\Form\Booking\BookTypes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('book', EntityType::class, array(
                'class' => 'AppBundle\Entity\Booking\Book',
                'query_builder' => function(EntityRepository $er) use($options) {
                    return $er->createQueryBuilder('b')
                        ->where('b.type = :bookType')
                        ->setParameter('bookType', BookTypes::PLAYER)
                        ->orderBy('b.description', 'ASC');
                },
                'choice_label' => 'description',
                'expanded' => false,
                'multiple' => false,
                'placeholder' => ""
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }
}
