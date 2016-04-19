<?php
/**
 * Created by PhpStorm.
 * User: bgolek
 * Date: 2016-04-19
 * Time: 09:39
 */

namespace AppBundle\Form\Import;

use AppBundle\Entity\Import\BankData;
use AppBundle\Form\Booking\BookTypes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BankDataType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bank_account', EntityType::class, [
                'class' => 'AppBundle\Entity\Booking\Book',
                'query_builder' => function(EntityRepository $er) use($options) {
                    return $er->createQueryBuilder('b')
                              ->where('b.type = :bookType')
                              ->setParameter('bookType', BookTypes::BANK)
                              ->orderBy('b.description', 'ASC');
                },
                'choice_label' => 'description',
                'expanded' => false,
                'multiple' => false
            ])
            ->add('commit_date', ChoiceType::class, ['choices' => $options['columns']])
            ->add('transaction_date', ChoiceType::class, ['choices' => $options['columns']])
            ->add('title', ChoiceType::class, ['choices' => $options['columns']])
            ->add('account', ChoiceType::class, ['choices' => $options['columns']])
            ->add('account_number', ChoiceType::class, ['choices' => $options['columns']])
            ->add('amount', ChoiceType::class, ['choices' => $options['columns']])
            ->add('journal_description_template')
            ->add('import', SubmitType::class);
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'columns' => []
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => BankData::class,
            'columns' => []
        ));
    }
}