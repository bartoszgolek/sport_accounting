<?php
    /**
     * Created by PhpStorm.
     * User: bg
     * Date: 14.04.16
     * Time: 19:57
     */

    namespace AppBundle\Form\Type;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\OptionsResolver\OptionsResolver;


    /**
     *
     * @license MIT
     * @author Bartosz Gołek <bartosz.golek@gmail.com>
     **/
    class DatePickerType extends AbstractType
    {
        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults(
                array(
                    'html5' => true,
                    'widget' => 'single_text',
                    'format' => DateType::HTML5_FORMAT
                )
            );
        }

        public function getParent()
        {
            return DateType::class;
        }
    }