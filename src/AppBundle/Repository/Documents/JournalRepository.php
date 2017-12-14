<?php

namespace AppBundle\Repository\Documents;


use AppBundle\Entity\Documents\Journal;
use AppBundle\Entity\Documents\JournalPosition;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

/** */
class JournalRepository extends EntityRepository
{
    /**
     * @param Journal                                $journal
     * @param ArrayCollection|JournalPosition[]|null $originalPositions
     */
    public function save(Journal $journal, ArrayCollection $originalPositions = null)
    {
        if ($originalPositions != null)
        {
            foreach ($originalPositions as $position) {
                if (false === $journal->getPositions()->contains($position)) {
                    $this->_em->remove($position);
                }
            }
        }

        $this->_em->persist($journal);
        $this->_em->flush();
    }

    /**
     * @param Journal $journal
     */
    public function delete(Journal $journal)
    {
        $this->_em->remove($journal);
        $this->_em->flush();
    }
}
