<?php
    /**
     * Created by PhpStorm.
     * User: bg
     * Date: 14.04.16
     * Time: 19:57
     */

    namespace AppBundle\Form\Type;

    use AppBundle\Form\Documents\JournalTypes;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\CollectionType;
    use Symfony\Component\OptionsResolver\OptionsResolver;


    /**
     *
     * @license MIT
     * @author Bartosz Gołek <bartosz.golek@gmail.com>
     **/
    class RowsCollectionType extends AbstractType
    {
        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults(array('class' => 'table table-striped table-hover'));
        }

        public function getParent()
        {
            return CollectionType::class;
        }
    }