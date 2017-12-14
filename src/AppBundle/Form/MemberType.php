<?php

namespace AppBundle\Form;

use AppBundle\Entity\Booking\Book;
use AppBundle\Entity\Member;
use AppBundle\Entity\Tag;
use AppBundle\Form\Booking\BookTypes;
use AppBundle\Repository\TagRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('book', EntityType::class, [
                'class' => Book::class,
                'query_builder' => function(EntityRepository $er) use($options) {
                    return $er->createQueryBuilder('b')
                              ->where('b.type = :bookType')
                              ->setParameter('bookType', BookTypes::MEMBER)
                              ->orderBy('b.description', 'ASC');
                },
                'choice_label' => 'description',
                'expanded' => false,
                'multiple' => false,
                'placeholder' => ""
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'query_builder' => function(TagRepository $tr) use($options) {
                    return $tr->createQueryBuilder('t')
                              ->orderBy('t.name', 'ASC');
                },
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'placeholder' => ""
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Member::class
        ]);
    }
}
