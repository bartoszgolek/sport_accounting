<?php

namespace AppBundle\Entity\Documents;

use Doctrine\ORM\Mapping as ORM;

/**
 * JournalPosition
 *
 * @ORM\Table(name="documents_journal_position")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Documents\JournalPositionRepository")
 */
class JournalPosition
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
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Journal", inversedBy="positions")
     * @ORM\JoinColumn(name="journal_id", referencedColumnName="id")
     */
    private $journal;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="debit", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $debit;

    /**
     * @var string
     *
     * @ORM\Column(name="credit", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $credit;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Booking\Book")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id")
     */
    private $book;

    /**
     * @var string
     *
     * @ORM\Column(name="voucher", type="string", length=255)
     */
    private $voucher;


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
     * Set journal
     *
     * @param Journal $journal
     *
     * @return JournalPosition
     */
    public function setJournal(Journal $journal)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get journal
     *
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return JournalPosition
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
     * Set debit
     *
     * @param string $debit
     *
     * @return JournalPosition
     */
    public function setDebit($debit)
    {
        $this->debit = $debit;

        return $this;
    }

    /**
     * Get debit
     *
     * @return string
     */
    public function getDebit()
    {
        return $this->debit;
    }

    /**
     * Set credit
     *
     * @param string $credit
     *
     * @return JournalPosition
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return string
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set voucher
     *
     * @param string $voucher
     *
     * @return JournalPosition
     */
    public function setVoucher($voucher)
    {
        $this->voucher = $voucher;

        return $this;
    }

    /**
     * Get voucher
     *
     * @return string
     */
    public function getVoucher()
    {
        return $this->voucher;
    }

    /**
     * Set book
     *
     * @param \AppBundle\Entity\Booking\Book $book
     *
     * @return JournalPosition
     */
    public function setBook(\AppBundle\Entity\Booking\Book $book = null)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     * @return \AppBundle\Entity\Booking\Book
     */
    public function getBook()
    {
        return $this->book;
    }
}
