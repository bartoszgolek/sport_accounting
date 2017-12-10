<?php

namespace AppBundle\Controller\Booking;

use AppBundle\Repository\Booking\BookRepository;
use AppBundle\Utils\Form;
use AppBundle\Utils\Redirect;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Booking\Book;

/**
 * @Route("/booking/book", service="AppBundle\Controller\Booking\DeleteBookController")
 */
class DeleteBookController
{
    /** @var BookRepository */
    private $bookRepository;

    /** @var Form */
    private $form;

    /** @var Redirect */
    private $redirect;

    /**
     * @param BookRepository $bookRepository
     * @param Form $form
     * @param Redirect $redirect
     */
    public function __construct(
        BookRepository $bookRepository,
        Form $form,
        Redirect $redirect)
    {
        $this->bookRepository = $bookRepository;
        $this->form = $form;
        $this->redirect = $redirect;
    }

    /**
     * @Route("/{id}", name="booking_book_delete")
     * @Method("DELETE")
     *
     * @param Book $book
     *
     * @return RedirectResponse
     */
    public function deleteAction(Book $book): RedirectResponse
    {
        $form = $this->createDeleteForm($book);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookRepository->delete($book);
        }

        return $this->redirect->toRoute('booking_book_index');
    }

    /**
     * @param Book $booking_book
     *
     * @return FormInterface
     */
    private function createDeleteForm(Book $booking_book)
    {
        return $this->form->createDelete('booking_book_delete', ['id' => $booking_book->getId()]);
    }
}
