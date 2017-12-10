<?php

namespace AppBundle\Controller\Booking;

use AppBundle\Entity\Booking\AccountsFilter;
use AppBundle\Entity\Booking\Transaction;
use AppBundle\Entity\Booking\TransactionFilter;
use AppBundle\Form\Booking\AccountsFilterType;
use AppBundle\Form\Booking\BookTypes;
use AppBundle\Form\Booking\TransactionFilterType;
use AppBundle\Repository\Booking\BookRepository;
use AppBundle\Repository\Booking\TransactionRepository;
use AppBundle\Utils\Form;
use AppBundle\Utils\Redirect;
use AppBundle\Utils\View;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Booking\Book;
use AppBundle\Form\Booking\BookType;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/booking/book", service="AppBundle\Controller\Booking\BookIndexController")
 */
class BookIndexController
{
    /** @var BookRepository */
    private $bookRepository;

    /** @var TransactionRepository */
    private $transactionRepository;

    /** @var Form */
    private $form;

    /** @var View */
    private $view;

    /** @var Redirect */
    private $redirect;

    /**
     * @param BookRepository $bookRepository
     * @param TransactionRepository $transactionRepository
     * @param Form $form
     * @param View $view
     */
    public function __construct(
        BookRepository $bookRepository,
        TransactionRepository $transactionRepository,
        Form $form,
        View $view)
    {
        $this->bookRepository = $bookRepository;
        $this->transactionRepository = $transactionRepository;
        $this->form = $form;
        $this->view = $view;
    }

    /**
     * @Route("/", name="booking_book_index")
     * @Method("GET")
     */
    public function indexAction(): Response
    {
        $booking_books = $this->bookRepository->findAll();

        return $this->view->render('booking/book/index.html.twig', [
            'booking_books' => $booking_books,
            'book_types' => BookTypes::getArray()
        ]);
    }

    /**
     * @Route("/balance_report", name="booking_book_balanceReport")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function balanceReportAction(Request $request)
    {
        $filter = new AccountsFilter();
        $filterForm = $this->form->create(AccountsFilterType::class, $request, $filter);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            /** @var ClickableInterface $resetButton */
            $resetButton = $filterForm->get('reset');
            if ($resetButton->isClicked()) {
                $filter = new AccountsFilter();
                $filterForm = $this->form->createEmpty(AccountsFilterType::class, $filter);
            }
        }

        $books = $this->bookRepository
            ->createQueryBuilder('b')
            ->join("b.transactions", "t")
            ->where(":type <= b.type")
            ->orderBy("b.description", "desc")
            ->setParameter("type", $filter->getType())
            ->select([
                "b.id",
                "b.description",
                "b.type",
                'debit' => 'SUM(t.debit) as debit',
                'credit' => 'SUM(t.credit) as credit',
                'balance' => '(-1 * SUM(t.debit)) + SUM(t.credit) as balance',
            ])
            ->groupBy("b.id")
            ->getQuery()
            ->execute();

        return $this->view->render('booking/book/balanceReport.html.twig', [
            'booking_books' => $books,
            'filter_form' => $filterForm->createView(),
            'book_types' => BookTypes::getArray()
        ]);
    }

    /**
     * @Route("/new", name="booking_book_new")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $booking_book = new Book();
        $form = $this->form->create(BookType::class, $request, $booking_book);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookRepository->save($booking_book);


            return $this->redirect->toRoute('booking_book_show', ['id' => $booking_book->getId()]);
        }

        return $this->view->render('booking/book/new.html.twig', [
            'booking_book' => $booking_book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="booking_book_show")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Book $book
     *
     * @return Response
     */
    public function showAction(Request $request, Book $book)
    {
        $filter = new TransactionFilter();
        $filterForm = $this->form->create(TransactionFilterType::class, $request, $filter);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            /** @var ClickableInterface $resetButton */
            $resetButton = $filterForm->get('reset');
            if ($resetButton->isClicked()) {
                $filter = new TransactionFilter();
                $filterForm = $this->form->createEmpty(TransactionFilterType::class, $filter);
            }
        }

        /** @var Transaction[] $transactions */
        $transactions = $this->transactionRepository
            ->createQueryBuilder('t')
            ->where(":from_date <= t.date")
            ->andWhere(":to_date >= t.date")
            ->andWhere(":book_id = t.book")
            ->orderBy("t.date", "desc")
            ->setParameter("from_date", $filter->getFromDate())
            ->setParameter("to_date", $filter->getToDate())
            ->setParameter("book_id", $book->getId())
            ->getQuery()
            ->execute();

        $debit_sum_before = 0;
        $credit_sum_before = 0;
        $value_sum_before = 0;

        $balance = $this->transactionRepository
            ->createQueryBuilder('t')
            ->select([
                'debit' => 'SUM(t.debit)',
                'credit' => 'SUM(t.credit)',
            ])
            ->where(":from_date > t.date")
            ->andWhere(":book_id = t.book")
            ->groupBy("t.book")
            ->setParameter("from_date", $filter->getFromDate())
            ->setParameter("book_id", $book->getId())
            ->getQuery()
            ->execute();

        if (count($balance) > 0) {
            $debit_sum_before = $balance[0][1];
            $credit_sum_before = $balance[0][2];
            $value_sum_before = (-1 * $debit_sum_before) + $credit_sum_before;
        }

        $debit_sum_after = $debit_sum_before;
        $credit_sum_after = $credit_sum_before;
        $value_sum_after = $value_sum_before;
        foreach ($transactions as $transaction){
            $debit_sum_after += (float)$transaction->getDebit();
            $credit_sum_after += (float)$transaction->getCredit();
            $value_sum_after += (float)$transaction->getValue();
        }

        return $this->view->render('booking/my_book/show.html.twig', [
            'book' => $book,
            'transactions' => $transactions,
            'filter_form' => $filterForm->createView(),
            'credit_sum_after' => sprintf("%20.2f", $credit_sum_after),
            'debit_sum_after' => sprintf("%20.2f", $debit_sum_after),
            'value_sum_after' => sprintf("%20.2f", $value_sum_after),
            'credit_sum_before' => sprintf("%20.2f", $credit_sum_before),
            'debit_sum_before' => sprintf("%20.2f", $debit_sum_before),
            'value_sum_before' => sprintf("%20.2f", $value_sum_before),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="booking_book_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Book $book
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, Book $book)
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
     * @Route("/{id}", name="booking_book_delete")
     * @Method("DELETE")
     *
     * @param Book $book
     *
     * @return RedirectResponse
     */
    public function deleteAction(Book $book)
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
