<?php
/**
 * Created by PhpStorm.
 * User: bg
 * Date: 16.04.16
 * Time: 17:55
 */

namespace AppBundle\Business;


use AppBundle\Entity\Booking\Transaction;
use AppBundle\Entity\Documents\Journal;
use AppBundle\Entity\Documents\JournalPosition;
use Doctrine\ORM\EntityManager;

class CommitJournal
{
    /**
     * @var EntityManager
     */
    protected $em;

    function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function commit(Journal $journal)
    {
        try {
            $commitTime = new \DateTime();

            $this->em->beginTransaction();
            foreach ($journal->getPositions() as $position) {
                /* @var JournalPosition $position */
                $transaction = new Transaction();
                $transaction->setBook($position->getBook());
                $position->getBook()->addTransaction($transaction);
                $transaction->setDebit($position->getDebit());
                $transaction->setCredit($position->getCredit());
                $transaction->setDescription($position->getDescription());
                $transaction->setDate($position->getDate());
                $transaction->setVoucher($position->getVoucher());

                $this->em->persist($transaction);
            }

            $journal->setCommitted(true);
            $journal->setCommitTime($commitTime);
            $this->em->persist($journal);

            $this->em->commit();
            $this->em->flush();
        }
        catch (\Exception $ex)
        {
            $this->em->rollback();
            throw $ex;
        }
    }
}