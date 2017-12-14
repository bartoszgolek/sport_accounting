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
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/book", service="AppBundle\Controller\Booking\DeleteBookController")
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
     * @param Request $request
     * @param Book    $book
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Book $book): RedirectResponse
    {
        $form = $this->form->createDelete('booking_book_delete', $book->getId());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookRepository->delete($book);
        }

        return $this->redirect->toRoute('booking_book_index');
    }
}
