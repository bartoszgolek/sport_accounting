<?php
/**
 * Created by PhpStorm.
 * User: bgolek
 * Date: 2016-04-18
 * Time: 08:57
 */

namespace AppBundle\Entity;


class LineSeparators
{
    const LF = "LF";
    const CR = "CR";
    const CRLF = "CRLF";

    private static $codes = [
        self::LF => "\n",
        self::CR => "\r",
        self::CRLF => "\r\n",
    ];

    private static $names = [
        self::LF => self::LF_NAME,
        self::CR => self::CR_NAME,
        self::CRLF => self::CRLF_NAME,
    ];

    const LF_NAME = 'Unix (LF)';
    const CR_NAME = 'Classic Mac (CR)';
    const CRLF_NAME = 'Windows (CRLF)';

    static function getName($lineSeparator)
    {
        $lineSeparators = self::getArray();

        return $lineSeparators[$lineSeparator];
    }

    static function getCode($lineSeparator)
    {
        return LineSeparators::$codes[$lineSeparator];
    }

    static function getArray()
    {
        return LineSeparators::$names;
    }
}