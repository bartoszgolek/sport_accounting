<?php
/**
 * Created by PhpStorm.
 * User: bg
 * Date: 17.04.16
 * Time: 11:44
 */

namespace AppBundle\Entity\Import;

use Symfony\Component\Validator\Constraints as Assert;


class UploadFile
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
}