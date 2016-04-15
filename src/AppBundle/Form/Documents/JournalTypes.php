<?php
    /**
     * Created by PhpStorm.
     * User: bg
     * Date: 06.04.16
     * Time: 22:23
     */

    namespace AppBundle\Form\Documents;

    /**
     *
     * @license MIT
     * @author Bartosz Gołek <bartosz.golek@gmail.com>
     **/
    class JournalTypes
    {
        const BASIC = 0;
        const BASIC_NAME = 'Basic';

        static function getName($bookType)
        {
            $bookTypes = self::getArray();

            return $bookTypes[$bookType];
        }

        static function getArray()
        {
            return array(
                self::BASIC => self::BASIC_NAME
            );
        }
    }