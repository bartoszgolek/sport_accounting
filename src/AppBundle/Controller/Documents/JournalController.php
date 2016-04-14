<?php

namespace AppBundle\Controller\Documents;

use AppBundle\Form\Documents\JournalTypes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Documents\Journal;


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

        $documents_journals = $em->getRepository('AppBundle:Documents\Journal')->findAll();

        return $this->render('documents/journal/index.html.twig', array(
            'documents_journals' => $documents_journals,
            'journal_types' => JournalTypes::getArray()
        ));
    }

    /**
     * Creates a new Documents\Journal entity.
     *
     * @Route("/new", name="documents_journal_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $documents_journal = new Journal();
        $form = $this->createForm('AppBundle\Form\Documents\JournalType', $documents_journal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($documents_journal);
            $em->flush();

            return $this->redirectToRoute('documents_journal_show', array('id' => $documents_journal->getId()));
        }

        return $this->render('documents/journal/new.html.twig', array(
            'documents_journal' => $documents_journal,
            'journal_types' => JournalTypes::getArray(),
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Documents\Journal entity.
     *
     * @Route("/{id}", name="documents_journal_show")
     * @Method("GET")
     */
    public function showAction(Journal $documents_journal)
    {
        $deleteForm = $this->createDeleteForm($documents_journal);

        return $this->render('documents/journal/show.html.twig', array(
            'documents_journal' => $documents_journal,
            'journal_types' => JournalTypes::getArray(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Documents\Journal entity.
     *
     * @Route("/{id}/edit", name="documents_journal_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Journal $documents_journal)
    {
        if ($documents_journal->getCommitted() == true) {
            $this->addFlash('error', 'Cannot edit committed journal!');
            return $this->redirectToRoute('documents_journal_index');
        }

        $deleteForm = $this->createDeleteForm($documents_journal);
        $editForm = $this->createForm('AppBundle\Form\Documents\JournalType', $documents_journal);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($documents_journal);
            $em->flush();

            return $this->redirectToRoute('documents_journal_edit', array('id' => $documents_journal->getId()));
        }

        return $this->render('documents/journal/edit.html.twig', array(
            'documents_journal' => $documents_journal,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Documents\Journal entity.
     *
     * @Route("/{id}", name="documents_journal_delete")
     * @Method("DELETE")
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
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Journal $documents_journal)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('documents_journal_delete', array('id' => $documents_journal->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
