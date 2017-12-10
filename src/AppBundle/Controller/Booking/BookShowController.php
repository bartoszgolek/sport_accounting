<?php

namespace AppBundle\Controller\Booking;

use AppBundle\Entity\Booking\Transaction;
use AppBundle\Entity\Booking\TransactionFilter;
use AppBundle\Form\Booking\TransactionFilterType;
use AppBundle\Repository\Booking\BookRepository;
use AppBundle\Repository\Booking\TransactionRepository;
use AppBundle\Utils\Form;
use AppBundle\Utils\View;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Booking\Book;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/booking/book", service="AppBundle\Controller\Booking\BookShowController")
 */
class BookShowController
{
    /** @var TransactionRepository */
    private $transactionRepository;

    /** @var Form */
    private $form;

    /** @var View */
    private $view;

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
        $this->transactionRepository = $transactionRepository;
        $this->form = $form;
        $this->view = $view;
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
    public function showAction(Request $request, Book $book): Response
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
}
