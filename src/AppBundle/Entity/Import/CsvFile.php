<?php
/**
 * Created by PhpStorm.
 * User: bg
 * Date: 17.04.16
 * Time: 11:44
 */

namespace AppBundle\Entity\Import;

use Symfony\Component\Validator\Constraints as Assert;


class CsvFile
{
    /**
     * @Assert\NotBlank(message="Please, upload the bank CSV file.")
     * @Assert\File(mimeTypes={ "text/plain" })
     */
    private $file;

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;

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
}