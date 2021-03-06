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
        const MEMBER = 1;
        const SCHOOL = 2;

        const BANK_NAME = 'Bank';
        const MEMBER_NAME = 'Member';
        const SCHOOL_NAME = 'School';

        static function getName($bookType)
        {
            $bookTypes = self::getArray();

            return $bookTypes[$bookType];
        }

        static function getArray()
        {
            return [
                self::BANK => self::BANK_NAME,
                self::MEMBER => self::MEMBER_NAME,
                self::SCHOOL => self::SCHOOL_NAME
            ];
        }
    }