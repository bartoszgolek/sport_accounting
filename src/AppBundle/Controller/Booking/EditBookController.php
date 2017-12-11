<?php

namespace AppBundle\Controller\Booking;

use AppBundle\Repository\Booking\BookRepository;
use AppBundle\Utils\Form;
use AppBundle\Utils\Redirect;
use AppBundle\Utils\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Booking\Book;
use AppBundle\Form\Booking\BookType;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/book", service="AppBundle\Controller\Booking\EditBookController")
 */
class EditBookController
{
    /** @var BookRepository */
    private $bookRepository;

    /** @var Form */
    private $form;

    /** @var View */
    private $view;

    /** @var Redirect */
    private $redirect;

    /**
     * @param BookRepository $bookRepository
     * @param Form $form
     * @param View $view
     * @param Redirect $redirect
     */
    public function __construct(
        BookRepository $bookRepository,
        Form $form,
        View $view,
        Redirect $redirect)
    {
        $this->bookRepository = $bookRepository;
        $this->form = $form;
        $this->view = $view;
        $this->redirect = $redirect;
    }

    /**
     * @Route("/{id}/edit", name="booking_book_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Book $book
     *
     * @return Response
     */
    public function editAction(Request $request, Book $book): Response
    {
        $deleteForm = $this->createDeleteForm($book);
        $editForm = $this->form->create(BookType::class, $request, $book);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->bookRepository->save($book);

            return $this->redirect->toRoute('booking_book_edit', ['id' => $book->getId()]);
        }

        return $this->view->render('booking/book/edit.html.twig', [
            'booking_book' => $book,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @param Book $booking_book
     *
     * @return FormInterface
     */
    private function createDeleteForm(Book $booking_book)
    {
        return $this->form->createDelete('booking_book_delete', $booking_book->getId());
    }
}
