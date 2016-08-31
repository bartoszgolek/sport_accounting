<?php

namespace AppBundle\Controller\Booking;

use AppBundle\Entity\Booking\AccountsFilter;
use AppBundle\Entity\Booking\Transaction;
use AppBundle\Entity\Booking\TransactionFilter;
use AppBundle\Form\Booking\AccountsFilterType;
use AppBundle\Form\Booking\BookTypes;
use AppBundle\Form\Booking\TransactionFilterType;
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
     * Lists all Booking\Book entities.
     *
     * @Route("/balancereport", name="booking_book_balanceReport")
     * @Method({"GET", "POST"})
     */
    public function balanceReportAction(Request $request)
    {
        $filter = new AccountsFilter();
        $filterForm = $this->createForm(AccountsFilterType::class, $filter);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            if ($filterForm->get('reset')->isClicked()) {
                $filter = new AccountsFilter();
                $filterForm = $this->createForm(AccountsFilterType::class, $filter);
            }
        }

        $em = $this->getDoctrine()->getManager();
        $bookRepository = $em->getRepository(Book::class);
        $books = $bookRepository
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

        return $this->render('booking/book/balanceReport.html.twig', array(
            'booking_books' => $books,
            'filter_form' => $filterForm->createView(),
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
        $form = $this->createForm(BookType::class, $booking_book);
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
     * @Method({"GET", "POST"})
     */
    public function showAction(Request $request, Book $book)
    {
        $filter = new TransactionFilter();
        $filterForm = $this->createForm(TransactionFilterType::class, $filter);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            if ($filterForm->get('reset')->isClicked()) {
                $filter = new TransactionFilter();
                $filterForm = $this->createForm(TransactionFilterType::class, $filter);
            }
        }

        $em = $this->getDoctrine()->getManager();
        $transactionRepository = $em->getRepository(Transaction::class);
        $transactions = $transactionRepository
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

        $saldo = $transactionRepository
            ->createQueryBuilder('t')
            ->select(array(
                'debit' => 'SUM(t.debit)',
                'credit' => 'SUM(t.credit)',
            ))
            ->where(":from_date > t.date")
            ->andWhere(":book_id = t.book")
            ->groupBy("t.book")
            ->setParameter("from_date", $filter->getFromDate())
            ->setParameter("book_id", $book->getId())
            ->getQuery()
            ->execute();

        if (count($saldo) > 0) {
            $debit_sum_before = $saldo[0][1];
            $credit_sum_before = $saldo[0][2];
            $value_sum_before = (-1 * $debit_sum_before) + $credit_sum_before;
        } else {
            $debit_sum_before = 0;
            $credit_sum_before = 0;
            $value_sum_before = 0;
        }

        $debit_sum_after = $debit_sum_before;
        $credit_sum_after = $credit_sum_before;
        $value_sum_after = $value_sum_before;
        foreach ($transactions as $transaction){
            $debit_sum_after += $transaction->getDebit();
            $credit_sum_after += $transaction->getCredit();
            $value_sum_after += $transaction->getValue();
        }

        return $this->render('booking/my_book/show.html.twig', array(
            'book' => $book,
            'transactions' => $transactions,
            'saldo' => $saldo,
            'filter_form' => $filterForm->createView(),
            'credit_sum_after' => sprintf("%20.2f", $credit_sum_after),
            'debit_sum_after' => sprintf("%20.2f", $debit_sum_after),
            'value_sum_after' => sprintf("%20.2f", $value_sum_after),
            'credit_sum_before' => sprintf("%20.2f", $credit_sum_before),
            'debit_sum_before' => sprintf("%20.2f", $debit_sum_before),
            'value_sum_before' => sprintf("%20.2f", $value_sum_before),
        ));
    }

    /**
     * Displays a form to edit an existing Booking\Book entity.
     *
     * @Route("/{id}/edit", name="booking_book_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Book $book)
    {
        $deleteForm = $this->createDeleteForm($book);
        $editForm = $this->createForm(BookType::class, $book);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('booking_book_edit', array('id' => $book->getId()));
        }

        return $this->render('booking/book/edit.html.twig', array(
            'booking_book' => $book,
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
    public function deleteAction(Request $request, Book $book)
    {
        $form = $this->createDeleteForm($book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($book);
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
