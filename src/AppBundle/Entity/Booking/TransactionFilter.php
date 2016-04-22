<?php
/**
 * Created by PhpStorm.
 * User: bg
 * Date: 08.04.16
 * Time: 23:36
 */

namespace AppBundle\Entity\Booking;


class TransactionFilter
{
    public function __construct()
    {
        $from_date       = new \DateTime('first day of this month');
        $from_date->modify( '-3 month' );
        $this->from_date = $from_date;
        $to_date         = new \DateTime('first day of this month');
        $to_date->modify( 'next month' );
        $this->to_date = $to_date;
    }

    /**
     * @var \DateTime
     */
    private $from_date;

    /**
     * @var \DateTime
     */
    private $to_date;

    /**
     * @return \DateTime
     */
    public function getFromDate()
    {
        return $this->from_date;
    }

    /**
     * @param \DateTime $from_date
     */
    public function setFromDate($from_date)
    {
        $this->from_date = $from_date;
    }

    /**
     * @return \DateTime
     */
    public function getToDate()
    {
        return $this->to_date;
    }

    /**
     * @param \DateTime $to_date
     */
    public function setToDate($to_date)
    {
        $this->to_date = $to_date;
    }
}