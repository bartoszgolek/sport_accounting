<?php
    /**
     * Created by PhpStorm.
     * User: bg
     * Date: 06.04.16
     * Time: 22:12
     */

    namespace AppBundle\Controller\Booking;

    use AppBundle\Entity\Booking\Invoice;
    use AppBundle\Entity\Documents\Journal;
    use AppBundle\Entity\Documents\JournalPosition;
    use AppBundle\Form\Documents\JournalTypes;
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
    class InvoiceController extends Controller
    {
        /**
         * Lists all Booking\Book entities.
         *
         * @Route("/booking/invoice/new", name="booking_invoice_new")
         * @Method({"GET", "POST"})
         */
        public function newAction(Request $request)
        {
            $invoice = new Invoice();
            $form = $this->createForm('AppBundle\Form\Booking\InvoiceType', $invoice);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $voucher = uniqid("V");
                $reinforcements_amount = $invoice->getReinforcementsCost() * $invoice->getNumberOfReinforcements();
                $per_player_amount = ($invoice->getAmount() - $reinforcements_amount) / $invoice->getPlayers()->count();

                $journal = new Journal();
                $journal->setDescription("Posting invoice " . $invoice->getInvoiceNumber());
                $journal->setType(JournalTypes::BASIC);

                $this->createJournalPosition(
                    $journal,
                    $voucher,
                    $invoice->getInvoiceDate(),
                    "Invoice number ".$invoice->getInvoiceNumber(),
                    $invoice->getSchool(),
                    $invoice->getAmount(),
                    null);
                if ($reinforcements_amount > 0) {
                    $this->createJournalPosition(
                        $journal,
                        $voucher,
                        $invoice->getInvoiceDate(),
                        "Reinforcements",
                        $invoice->getReinforcementsBook(),
                        null,
                        $reinforcements_amount);
                }

                foreach ($invoice->getPlayers() as $player) {
                    $this->createJournalPosition(
                        $journal,
                        $voucher,
                        $invoice->getInvoiceDate(),
                        "Participation",
                        $player,
                        null,
                        $per_player_amount);
                }

                $em->persist($journal);
                $em->flush();

                return $this->redirectToRoute('documents_journal_edit', array('id' => $journal->getId()));
            }

            return $this->render('booking/invoice/new.html.twig', array(
                'invoice' => $invoice,
                'form' => $form->createView(),
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