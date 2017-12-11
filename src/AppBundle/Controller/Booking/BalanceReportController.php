<?php

namespace AppBundle\Controller\Booking;

use AppBundle\Entity\Booking\AccountsFilter;
use AppBundle\Form\Booking\AccountsFilterType;
use AppBundle\Form\Booking\BookTypes;
use AppBundle\Repository\Booking\BookRepository;
use AppBundle\Utils\Form;
use AppBundle\Utils\View;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/", service="AppBundle\Controller\Booking\BalanceReportController")
 */
class BalanceReportController
{
    /** @var BookRepository */
    private $bookRepository;

    /** @var Form */
    private $form;

    /** @var View */
    private $view;

    /**
     * @param BookRepository $bookRepository
     * @param Form $form
     * @param View $view
     */
    public function __construct(
        BookRepository $bookRepository,
        Form $form,
        View $view)
    {
        $this->bookRepository = $bookRepository;
        $this->form = $form;
        $this->view = $view;
    }

    /**
     * @Route("/balance_report", name="booking_book_balanceReport")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function balanceReportAction(Request $request): Response
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
}
