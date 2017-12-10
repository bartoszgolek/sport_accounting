<?php
    namespace AppBundle\Form\Documents;

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
            return [
                self::BASIC => self::BASIC_NAME
            ];
        }
    }