<?php
    /**
     * Created by PhpStorm.
     * User: bg
     * Date: 06.04.16
     * Time: 22:23
     */

    namespace AppBundle\Form\Booking;


    /**
     *
     * @license MIT
     * @author Bartosz Gołek <bartosz.golek@gmail.com>
     **/
    class BookTypes
    {
        const BANK = 0;
        const PLAYER = 0;
        const SCHOOL = 0;

        static function getName($bookType)
        {
            $bookTypes = array(
                BookTypes::BANK => 'Bank',
                BookTypes::PLAYER => 'Player',
                BookTypes::SCHOOL => 'School'
            );

            return $bookTypes[$bookType];
        }

        static function getOptions()
        {
            return array(
                'Bank' => BookTypes::BANK,
                'Player' => BookTypes::PLAYER,
                'School' => BookTypes::SCHOOL
            );
        }
    }