<?php

namespace AppBundle\Controller\Documents;

use AppBundle\Repository\Documents\JournalRepository;
use AppBundle\Utils\Form;
use AppBundle\Utils\Redirect;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Documents\Journal;


/**
 * @Route("/journal", service="AppBundle\Controller\Documents\DeleteJournalController")
 */
class DeleteJournalController
{
    /** @var JournalRepository */
    private $journalRepository;

    /** @var Redirect */
    private $redirect;

    /** @var Form */
    private $form;

    /**
     * @param JournalRepository $journalRepository
     * @param Redirect          $redirect
     * @param Form              $form
     */
    public function __construct(JournalRepository $journalRepository, Redirect $redirect, Form $form)
    {
        $this->journalRepository = $journalRepository;
        $this->redirect = $redirect;
        $this->form = $form;
    }

    /**
     * @Route("/{id}", name="documents_journal_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Journal $journal
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Journal $journal)
    {
        $form = $this->form->createDelete('documents_journal_delete', $journal->getId());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->journalRepository->delete($journal);
        }

        return $this->redirect->toRoute('documents_journal_index');
    }
}
