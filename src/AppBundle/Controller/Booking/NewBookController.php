<?php

namespace AppBundle\Controller\Booking;

use AppBundle\Repository\Booking\BookRepository;
use AppBundle\Utils\Form;
use AppBundle\Utils\Redirect;
use AppBundle\Utils\View;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Booking\Book;
use AppBundle\Form\Booking\BookType;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/book", service="AppBundle\Controller\Booking\NewBookController")
 */
class NewBookController
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
     * @Route("/new", name="booking_book_new")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function newAction(Request $request): Response
    {
        $book = new Book();
        $form = $this->form->create(BookType::class, $request, $book);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookRepository->save($book);


            return $this->redirect->toRoute('booking_book_show', ['id' => $book->getId()]);
        }

        return $this->view->render('booking/book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }
}
