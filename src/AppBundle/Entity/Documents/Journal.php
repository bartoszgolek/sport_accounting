<?php

namespace AppBundle\Entity\Documents;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Journal
 *
 * @ORM\Table(name="documents_journal")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Documents\JournalRepository")
 */
class Journal
{
    public function __construct()
    {
        $this->positions = new ArrayCollection();
    }

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
     * @ORM\Column(name="committed", type="boolean")
     */
    private $committed = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="commit_time", type="datetimetz", nullable=true)
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
     * Set committed
     *
     * @param boolean $committed
     *
     * @return Journal
     */
    public function setCommitted($committed)
    {
        $this->committed = $committed;

        return $this;
    }

    /**
     * Get committed
     *
     * @return bool
     */
    public function getCommitted()
    {
        return $this->committed;
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

    /**
     * @ORM\OneToMany(targetEntity="JournalPosition", mappedBy="journal", cascade={"persist", "remove"})
     */
    private $positions;

    /**
     * Add position
     *
     * @param JournalPosition $position
     *
     * @return Journal
     */
    public function addPosition(JournalPosition $position)
    {
        $position->setJournal($this);
        $this->positions[] = $position;

        return $this;
    }

    /**
     * Remove transaction
     *
     * @param JournalPosition $position
     */
    public function removeTransaction(JournalPosition $position)
    {
        $this->positions->removeElement($position);
    }

    /**
     * Get positions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPositions()
    {
        return $this->positions;
    }
}
