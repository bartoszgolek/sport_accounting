<?php

namespace AppBundle\Controller\Documents;

use AppBundle\Form\Documents\JournalType;
use AppBundle\Repository\Documents\JournalRepository;
use AppBundle\Utils\Form;
use AppBundle\Utils\Forwarder;
use AppBundle\Utils\Notification;
use AppBundle\Utils\Redirect;
use AppBundle\Utils\View;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Documents\Journal;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/journal", service="AppBundle\Controller\Documents\EditJournalController")
 */
class EditJournalController
{
    /** @var Notification */
    private $notifications;

    /** @var Redirect */
    private $redirect;

    /** @var View */
    private $view;

    /** @var Form */
    private $form;

    /** @var Forwarder */
    private $forwarder;

    /** @var JournalRepository */
    private $journalRepository;

    /**
     * @param Notification      $notifications
     * @param Redirect          $redirect
     * @param View              $view
     * @param Form              $form
     * @param Forwarder         $forwarder
     * @param JournalRepository $journalRepository
     */
    public function __construct(Notification $notifications, Redirect $redirect, View $view, Form $form, Forwarder $forwarder, JournalRepository $journalRepository)
    {
        $this->notifications = $notifications;
        $this->redirect = $redirect;
        $this->view = $view;
        $this->form = $form;
        $this->forwarder = $forwarder;
        $this->journalRepository = $journalRepository;
    }

    /**
     * @Route("/{id}/edit", name="documents_journal_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Journal $journal
     *
     * @return Response
     */
    public function editAction(Request $request, Journal $journal)
    {
        if ($journal->getCommitted() == true) {
            $this->notifications->error('Cannot edit committed journal!');
            return $this->redirect->toRoute('documents_journal_index');
        }

        $originalPositions = $this->getOriginalPositions($journal);

        $deleteForm = $this->form->createDelete('documents_journal_delete', $journal->getId());
        $editForm = $this->form->create(JournalType::class, $request, $journal);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->journalRepository->save($journal, $originalPositions);

            if ($editForm->get('commit')->isClicked())
            {
                return $this->forwarder->forward('AppBundle\Controller\Documents\CommitJournalController::commitAction', ['journal' => $journal]);
            }

            return $this->redirect->toRoute('documents_journal_edit', ['id' => $journal->getId()]);
        }

        return $this->view->render('documents/journal/edit.html.twig', [
            'documents_journal' => $journal,
            'edit_form'         => $editForm->createView(),
            'delete_form'       => $deleteForm->createView(),
        ]);
    }

    /**
     * @param Journal $journal
     *
     * @return ArrayCollection
     */
    public function getOriginalPositions(Journal $journal): ArrayCollection
    {
        $originalPositions = new ArrayCollection();
        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($journal->getPositions() as $position) {
            $originalPositions->add($position);
        }
        return $originalPositions;
    }
}
