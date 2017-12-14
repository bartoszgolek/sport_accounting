<?php

namespace AppBundle\Controller\Documents;

use AppBundle\Form\Documents\JournalTypes;
use AppBundle\Utils\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Documents\Journal;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/journal", service="AppBundle\Controller\Documents\ShowJournalController")
 */
class ShowJournalController
{
    /** @var View */
    private $view;

    /** @var \AppBundle\Utils\Form */
    private $form;

    /**
     * @param View                  $view
     * @param \AppBundle\Utils\Form $form
     */
    public function __construct(View $view, \AppBundle\Utils\Form $form)
    {
        $this->view = $view;
        $this->form = $form;
    }

    /**
     * @Route("/{id}/show", name="documents_journal_show")
     * @Method("GET")
     *
     * @param Journal $journal
     *
     * @return Response
     */
    public function showAction(Journal $journal)
    {
        $deleteForm = $this->form->createDelete('documents_journal_delete', $journal->getId());

        return $this->view->render('documents/journal/show.html.twig', [
            'documents_journal' => $journal,
            'journal_types' => JournalTypes::getArray(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }
}
