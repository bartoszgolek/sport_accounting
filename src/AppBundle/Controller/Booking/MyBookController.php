<?php
    /**
     * Created by PhpStorm.
     * User: bg
     * Date: 06.04.16
     * Time: 22:12
     */

    namespace AppBundle\Controller\Booking;

    use AppBundle\Entity\Booking\Transaction;
    use AppBundle\Entity\Booking\TransactionFilter;
    use AppBundle\Entity\Documents\Journal;
    use AppBundle\Entity\Documents\JournalPosition;
    use AppBundle\Entity\User;
    use AppBundle\Form\Booking\TransactionFilterType;
    use DateTime;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

    /**
     *
     * @license MIT
     * @author Bartosz Gołek <bartosz.golek@gmail.com>
     **/
    class MyBookController extends Controller
    {
        /**
         * Lists all Booking\Book entities.
         *
         * @Route("/booking/my_book/show", name="booking_my_book_show")
         * @Method({"GET", "POST"})
         */
        public function showAction(Request $request)
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

            /** @var User $usr */
            $usr = $this->container->get('security.token_storage')->getToken()->getUser();

            $player = $usr->getPlayer();

            if ($player === null) {
                $this->addFlash('error', 'User has no attached player!');
                return $this->render('booking/my_book/nobook.html.twig');
            }

            $book = $player->getBook();

            if ($book === null) {
                $this->addFlash('error', 'User player has no attached account!');
                return $this->render('booking/my_book/nobook.html.twig');
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

            $balance = $transactionRepository
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

            if (count($balance) > 0) {
                $debit_sum_before = $balance[0][1];
                $credit_sum_before = $balance[0][2];
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
         * @param Journal $journal
         * @param $voucher
         * @param $book
         * @param $credit
         * @param $debit
         */
        protected function createJournalPosition(Journal $journal, $voucher, DateTime $date, $description, $book, $credit, $debit)
        {
            $pos = new JournalPosition();
            $pos->setVoucher($voucher);
            $pos->setDate($date);
            $pos->setDescription($description);
            $pos->setBook($book);
            $pos->setCredit($credit);
            $pos->setDebit($debit);
            $journal->addPosition($pos);
        }
    }