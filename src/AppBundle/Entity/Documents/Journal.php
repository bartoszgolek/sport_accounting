<?php

namespace AppBundle\Entity\Documents;

use Doctrine\ORM\Mapping as ORM;

/**
 * Journal
 *
 * @ORM\Table(name="documents_journal")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Documents\JournalRepository")
 */
class Journal
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var bool
     *
     * @ORM\Column(name="commited", type="boolean")
     */
    private $commited;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="commit_time", type="datetimetz")
     */
    private $commitTime;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Journal
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Journal
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set commited
     *
     * @param boolean $commited
     *
     * @return Journal
     */
    public function setCommited($commited)
    {
        $this->commited = $commited;

        return $this;
    }

    /**
     * Get commited
     *
     * @return bool
     */
    public function getCommited()
    {
        return $this->commited;
    }

    /**
     * Set commitTime
     *
     * @param \DateTime $commitTime
     *
     * @return Journal
     */
    public function setCommitTime($commitTime)
    {
        $this->commitTime = $commitTime;

        return $this;
    }

    /**
     * Get commitTime
     *
     * @return \DateTime
     */
    public function getCommitTime()
    {
        return $this->commitTime;
    }
}
