<?php
    /**
     * Created by PhpStorm.
     * User: bg
     * Date: 14.04.16
     * Time: 19:57
     */

    namespace AppBundle\Form\Type;

    use AppBundle\Form\Booking\BookTypes;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\OptionsResolver\OptionsResolver;


    /**
     *
     * @license MIT
     * @author Bartosz Gołek <bartosz.golek@gmail.com>
     **/
    class LineSeparatorType extends AbstractType
    {
        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults(array(
                'choices' => array(
                    'Unix (LF)' => "\n",
                    'Classic Mac (CR)' => "\r",
                    'Windows (CRLF)' => "\r\n"
                )
            ));
        }

        public function getParent()
        {
            return ChoiceType::class;
        }
    }