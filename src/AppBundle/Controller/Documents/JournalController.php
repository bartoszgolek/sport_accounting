<?php

namespace AppBundle\Controller\Documents;

use AppBundle\Entity\Documents\JournalPosition;
use AppBundle\Form\Booking\BookTypes;
use AppBundle\Form\Documents\JournalType;
use AppBundle\Form\Documents\JournalTypes;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Documents\Journal;
use Symfony\Component\HttpFoundation\Response;


/**
 * Documents\Journal controller.
 *
 * @Route("/documents_journal")
 */
class JournalController extends Controller
{
    /**
     * Lists all Documents\Journal entities.
     *
     * @Route("/", name="documents_journal_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $documents_journals = $em->getRepository(Journal::class)->findAll();

        return $this->render('documents/journal/index.html.twig', [
            'documents_journals' => $documents_journals,
            'book_types' => BookTypes::getArray(),
            'journal_types' => JournalTypes::getArray()
        ]);
    }

    /**
     * Creates a new Documents\Journal entity.
     *
     * @Route("/new", name="documents_journal_new")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function newAction(Request $request): Response
    {
        $journal = new Journal();
        $voucher = uniqid("V");

        $position = $this->createJournalPosition(
            $journal,
            $voucher,
            new \DateTime(),
            null,
            null,
            null,
            null
        );

        $journal->addPosition($position);
        $journal->addPosition($position);

        $form = $this->createForm(JournalType::class, $journal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($journal);
            $em->flush();

            return $this->redirectToRoute('documents_journal_show', ['id' => $journal->getId()]);
        }

        return $this->render('documents/journal/new.html.twig', [
            'journal' => $journal,
            'journal_types' => JournalTypes::getArray(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Journal $journal
     * @param $voucher
     * @param \DateTime $date
     * @param $description
     * @param $book
     * @param $credit
     * @param $debit
     */
    protected function createJournalPosition(Journal $journal, $voucher, \DateTime $date, $description, $book, $debit, $credit)
    {
        $pos = new JournalPosition();
        $pos->setVoucher($voucher)
            ->setDate($date)
            ->setDescription($description)
            ->setBook($book)
            ->setCredit($credit)
            ->setDebit($debit);

        return $pos;
    }

    /**
     * Finds and displays a Documents\Journal entity.
     *
     * @Route("/{id}", name="documents_journal_show")
     * @Method("GET")
     *
     * @param Journal $documents_journal
     *
     * @return Response
     */
    public function showAction(Journal $documents_journal)
    {
        $deleteForm = $this->createDeleteForm($documents_journal);

        return $this->render('documents/journal/show.html.twig', [
            'documents_journal' => $documents_journal,
            'journal_types' => JournalTypes::getArray(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Documents\Journal entity.
     *
     * @Route("/{id}/edit", name="documents_journal_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Journal $journal
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, Journal $journal)
    {
        if ($journal->getCommitted() == true) {
            $this->addFlash('error', 'Cannot edit committed journal!');
            return $this->redirectToRoute('documents_journal_index');
        }

        $deleteForm = $this->createDeleteForm($journal);

        $originalPositions = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($journal->getPositions() as $position) {
            $originalPositions->add($position);
        }

        $editForm = $this->createForm(JournalType::class, $journal);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // remove the relationship between the position and the Task
            foreach ($originalPositions as $position) {
                if (false === $journal->getPositions()->contains($position)) {
                    $em->remove($position);
                }
            }

            $em->persist($journal);
            $em->flush();


            if ($editForm->get('commit')->isClicked())
            {
                $commit_journal = $this->get('commit_journal');
                $commit_journal->commit($journal);
                return $this->redirectToRoute('documents_journal_index');
            }

            return $this->redirectToRoute('documents_journal_edit', ['id' => $journal->getId()]);
        }

        return $this->renderEditForm($journal, $editForm, $deleteForm);
    }

    /**
     * Deletes a Documents\Journal entity.
     *
     * @Route("/{id}", name="documents_journal_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Journal $documents_journal
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Journal $documents_journal)
    {
        $form = $this->createDeleteForm($documents_journal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($documents_journal);
            $em->flush();
        }

        return $this->redirectToRoute('documents_journal_index');
    }

    /**
     * Creates a form to delete a Documents\Journal entity.
     *
     * @param Journal $documents_journal The Documents\Journal entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Journal $documents_journal)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('documents_journal_delete', ['id' => $documents_journal->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @param Journal $documents_journal
     *
     * @param Form $editForm
     * @param Form $deleteForm
     *
     * @return Response
     */
    protected function renderEditForm(Journal $documents_journal, Form $editForm, Form $deleteForm)
    {
        return $this->render('documents/journal/edit.html.twig', [
            'documents_journal' => $documents_journal,
            'edit_form'         => $editForm->createView(),
            'delete_form'       => $deleteForm->createView(),
        ]);
    }
}
