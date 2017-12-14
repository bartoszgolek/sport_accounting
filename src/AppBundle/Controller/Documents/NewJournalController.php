<?php

namespace AppBundle\Controller\Documents;

use AppBundle\Entity\Documents\JournalPosition;
use AppBundle\Form\Documents\JournalType;
use AppBundle\Form\Documents\JournalTypes;
use AppBundle\Repository\Documents\JournalRepository;
use AppBundle\Utils\Form;
use AppBundle\Utils\Redirect;
use AppBundle\Utils\View;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Documents\Journal;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/journal", service="AppBundle\Controller\Documents\NewJournalController")
 */
class NewJournalController
{
    /** @var Form */
    private $form;

    /** @var Redirect */
    private $redirect;

    /** @var JournalRepository */
    private $journalRepository;

    /** @var View */
    private $view;

    /**
     * @param Form              $form
     * @param Redirect          $redirect
     * @param JournalRepository $journalRepository
     * @param View              $view
     */
    public function __construct(Form $form, Redirect $redirect, JournalRepository $journalRepository, View $view)
    {
        $this->form = $form;
        $this->redirect = $redirect;
        $this->journalRepository = $journalRepository;
        $this->view = $view;
    }

    /**
     * @Route("/new", name="documents_journal_new")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function newAction(Request $request): Response
    {
        $journal = new Journal();
        $voucher = uniqid("V");

        $position1 = $this->createJournalPosition(
            $voucher,
            new \DateTime(),
            null,
            null,
            null,
            null
        );
        $position2 = $this->createJournalPosition(
            $voucher,
            new \DateTime(),
            null,
            null,
            null,
            null
        );

        $journal->addPosition($position1);
        $journal->addPosition($position2);

        $form = $this->form->create(JournalType::class, $request, $journal);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->journalRepository->save($journal);

            return $this->redirect->toRoute('documents_journal_show', ['id' => $journal->getId()]);
        }

        return $this->view->render('documents/journal/new.html.twig', [
            'journal' => $journal,
            'journal_types' => JournalTypes::getArray(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param           $voucher
     * @param \DateTime $date
     * @param           $description
     * @param           $book
     * @param           $credit
     * @param           $debit
     *
     * @return JournalPosition
     */
    protected function createJournalPosition($voucher, \DateTime $date, $description, $book, $debit, $credit)
    {
        $pos = new JournalPosition();
        $pos->setVoucher($voucher)
            ->setDate($date)
            ->setDescription($description)
            ->setBook($book)
            ->setCredit($credit)
            ->setDebit($debit);

        return $pos;
    }
}
