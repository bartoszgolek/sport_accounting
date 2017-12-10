<?php

namespace AppBundle\Repository\Documents;


use AppBundle\Entity\Documents\Journal;
use Doctrine\ORM\EntityRepository;

/** */
class JournalRepository extends EntityRepository
{
    /**
     * @param Journal $journal
     */
    public function save(Journal $journal)
    {
        $this->_em->persist($journal);
        $this->_em->flush();
    }
}
