<?php
/**
 * Created by PhpStorm.
 * User: bg
 * Date: 17.04.16
 * Time: 11:41
 */

namespace AppBundle\Form\Import;

use AppBundle\Form\Type\FieldSeparatorType;
use AppBundle\Form\Type\LineSeparatorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CsvFileType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fileName', HiddenType::class)
            ->add('originalName', HiddenType::class)
            ->add('fieldSeparator', FieldSeparatorType::class)
            ->add('lineSeparator', LineSeparatorType::class)
            ->add('skip', IntegerType::class)
            ->add('hasHeaderRow', CheckboxType::class, array(
                'required' => false
            ))
            ->add('upload', SubmitType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Import\CsvFile'
        ));
    }
}