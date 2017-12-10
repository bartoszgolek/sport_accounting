<?php
/**
 * Created by PhpStorm.
 * User: bg
 * Date: 17.04.16
 * Time: 11:44
 */

namespace AppBundle\Entity\Import;


class CsvFile
{
    private $fileName;

    private $originalName;

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    private $fieldSeparator;

    public function getFieldSeparator()
    {
        return $this->fieldSeparator;
    }

    public function setFieldSeparator($fieldSeparator)
    {
        $this->fieldSeparator = $fieldSeparator;

        return $this;
    }

    private $lineSeparator;

    public function getLineSeparator()
    {
        return $this->lineSeparator;
    }

    public function setLineSeparator($lineSeparator)
    {
        $this->lineSeparator = $lineSeparator;

        return $this;
    }

    /**
     * @var integer
     */
    private $skip;

    public function getSkip()
    {
        return $this->skip;
    }

    public function setSkip($skip)
    {
        $this->skip = $skip;

        return $this;
    }

    /**
     * @var boolean
     */
    private $hasHeaderRow;

    public function getHasHeaderRow()
    {
        return $this->hasHeaderRow;
    }

    public function setHasHeaderRow($hasHeaderRow)
    {
        $this->hasHeaderRow = $hasHeaderRow;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * @param mixed $originalName
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;
    }
}