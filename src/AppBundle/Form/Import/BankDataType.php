<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Gołek
 * Date: 2016-04-19
 * Time: 09:39
 */

namespace AppBundle\Form\Import;

use AppBundle\Entity\Booking\Book;
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
        $columns = $options['columns'];
        $builder
            ->add('bank_account_column', EntityType::class, [
                'class' => Book::class,
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
            ->add('commit_date_column', ChoiceType::class, ['choices' => $columns])
            ->add('transaction_date_column', ChoiceType::class, ['choices' => $columns])
            ->add('title_column', ChoiceType::class, ['choices' => $columns])
            ->add('account_name_column', ChoiceType::class, ['choices' => $columns])
            ->add('account_number_column', ChoiceType::class, ['choices' => $columns])
            ->add('amount_column', ChoiceType::class, ['choices' => $columns])
            ->add('journal_description_template')
            ->add('import', SubmitType::class);
    }

    public function getDefaultOptions()
    {
        return [
            'columns' => []
        ];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BankData::class,
            'columns' => []
        ]);
    }
}