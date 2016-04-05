<?php

namespace AppBundle\Entity\Booking;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table(name="booking_transaction")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Booking\TransactionRepository")
 */
class Transaction
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
     * @ORM\ManyToOne(targetEntity="Book", inversedBy="book")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id")
     */
    private $book;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="credit", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $credit;

    /**
     * @var string
     *
     * @ORM\Column(name="debit", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $debit;


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
     * @return Transaction
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
     * Set credit
     *
     * @param string $credit
     *
     * @return Transaction
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
     * Set debit
     *
     * @param string $debit
     *
     * @return Transaction
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
     * Set book
     *
     * @param \AppBundle\Entity\Booking\Book $book
     *
     * @return Transaction
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
