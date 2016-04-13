<?php

namespace AppBundle\Controller\Booking;

use AppBundle\Form\Booking\BookTypes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Booking\Book;
use AppBundle\Form\Booking\BookType;

/**
 * Booking\Book controller.
 *
 * @Route("/booking_book")
 */
class BookController extends Controller
{
    /**
     * Lists all Booking\Book entities.
     *
     * @Route("/", name="booking_book_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $booking_books = $em->getRepository('AppBundle:Booking\Book')->findAll();

        return $this->render('booking/book/index.html.twig', array(
            'booking_books' => $booking_books,
            'book_types' => BookTypes::getArray()
        ));
    }

    /**
     * Creates a new Booking\Book entity.
     *
     * @Route("/new", name="booking_book_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $booking_book = new Book();
        $form = $this->createForm('AppBundle\Form\Booking\BookType', $booking_book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($booking_book);
            $em->flush();

            return $this->redirectToRoute('booking_book_show', array('id' => $booking_book->getId()));
        }

        return $this->render('booking/book/new.html.twig', array(
            'booking_book' => $booking_book,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Booking\Book entity.
     *
     * @Route("/{id}", name="booking_book_show")
     * @Method("GET")
     */
    public function showAction(Book $booking_book)
    {
        $deleteForm = $this->createDeleteForm($booking_book);

        return $this->render('booking/book/show.html.twig', array(
            'booking_book' => $booking_book,
            'book_types' => BookTypes::getArray(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Booking\Book entity.
     *
     * @Route("/{id}/edit", name="booking_book_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Book $booking_book)
    {
        $deleteForm = $this->createDeleteForm($booking_book);
        $editForm = $this->createForm('AppBundle\Form\Booking\BookType', $booking_book);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($booking_book);
            $em->flush();

            return $this->redirectToRoute('booking_book_edit', array('id' => $booking_book->getId()));
        }

        return $this->render('booking/book/edit.html.twig', array(
            'booking_book' => $booking_book,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Booking\Book entity.
     *
     * @Route("/{id}", name="booking_book_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Book $booking_book)
    {
        $form = $this->createDeleteForm($booking_book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($booking_book);
            $em->flush();
        }

        return $this->redirectToRoute('booking_book_index');
    }

    /**
     * Creates a form to delete a Booking\Book entity.
     *
     * @param Book $booking_book The Booking\Book entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Book $booking_book)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('booking_book_delete', array('id' => $booking_book->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
