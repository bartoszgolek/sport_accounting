<?php

namespace AppBundle\Controller\Documents;

use AppBundle\Business\CommitJournal;
use AppBundle\Utils\Redirect;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Documents\Journal;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/journal", service="AppBundle\Controller\Documents\CommitJournalController")
 */
class CommitJournalController
{
    /** @var CommitJournal */
    private $commit_journal;

    /** @var Redirect */
    private $redirect;

    /**
     * @param CommitJournal $commit_journal
     * @param Redirect      $redirect
     */
    public function __construct(CommitJournal $commit_journal, Redirect $redirect)
    {
        $this->commit_journal = $commit_journal;
        $this->redirect = $redirect;
    }

    /**
     * @Route("/{id}/commit", name="documents_journal_edit")
     * @Method({"POST"})
     *
     * @param Journal $journal
     *
     * @return Response
     */
    public function commitAction(Journal $journal)
    {
        $this->commit_journal->commit($journal);
        return $this->redirect->toRoute('documents_journal_index');
    }
}
