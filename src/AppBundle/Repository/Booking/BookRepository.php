<?php

namespace AppBundle\Repository\Booking;


use Doctrine\ORM\EntityRepository;

/**
 * BookRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookRepository extends EntityRepository
{
    public function delete($booking_book)
    {
        $this->_em->persist($booking_book);
        $this->_em->flush();
    }

    public function save($booking_book)
    {
        $this->_em->persist($booking_book);
        $this->_em->flush();
    }
}
