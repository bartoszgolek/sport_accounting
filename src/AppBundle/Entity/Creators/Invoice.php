<?php
/**
 * Created by PhpStorm.
 * User: bg
 * Date: 08.04.16
 * Time: 23:36
 */

namespace AppBundle\Entity\Creators;


use AppBundle\Entity\Booking\Book;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\DateTime;

class Invoice
{
    public function __construct()
    {
        $this->players      = new ArrayCollection();
        $this->invoice_date = new \DateTime();
    }

    /**
     * @var string
     */
    private $invoice_number;

    /**
     * @return string
     */
    public function getInvoiceNumber()
    {
        return $this->invoice_number;
    }

    /**
     * @param mixed $invoice_number
     */
    public function setInvoiceNumber($invoice_number)
    {
        $this->invoice_number = $invoice_number;
    }

    /**
     * @var \DateTime
     */
    private $invoice_date;

    /**
     * @return \DateTime
     */
    public function getInvoiceDate()
    {
        return $this->invoice_date;
    }

    /**
     * @param DateTime $invoice_date
     */
    public function setInvoiceDate($invoice_date)
    {
        $this->invoice_date = $invoice_date;
    }

    /**
     * @var int
     */
    private $number_of_reinforcements;

    /**
     * @return int
     */
    public function getNumberOfReinforcements()
    {
        return $this->number_of_reinforcements;
    }

    /**
     * @param int $number_of_reinforcements
     */
    public function setNumberOfReinforcements($number_of_reinforcements)
    {
        $this->number_of_reinforcements = $number_of_reinforcements;
    }

    private $players;

    /**
     * Add player
     *
     * @param Book $player
     *
     * @return Invoice
     */
    public function addPlayer(Book $player)
    {
        $this->players[] = $player;

        return $this;
    }

    /**
     * Remove player
     *
     * @param Book $player
     */
    public function removePlayer(Book $player)
    {
        $this->players->removeElement($player);
    }

    /**
     * Get players
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlayers()
    {
        return $this->players;
    }

    private $reinforcements_book;

    /**
     * Get Reinforcements Book
     *
     * @return Book
     */
    public function getReinforcementsBook()
    {
        return $this->reinforcements_book;
    }

    /**
     * Set Reinforcements Book
     *
     * @return Book
     */
    public function setReinforcementsBook(Book $book)
    {
        return $this->reinforcements_book = $book;
    }

    private $school;

    /**
     * Get School Book
     *
     * @return Book
     */
    public function getSchool()
    {
        return $this->school;
    }

    /**
     * Set School Book
     *
     * @return Book
     */
    public function setSchool(Book $school)
    {
        return $this->school = $school;
    }

    private $reinforcements_costs;

    /**
     * @return string
     */
    public function getReinforcementsCost()
    {
        return $this->reinforcements_costs;
    }

    /**
     * @param string $reinforcements_costs
     */
    public function setReinforcementsCost($reinforcements_costs)
    {
        $this->reinforcements_costs = $reinforcements_costs;
    }

    private $amount;

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }
}