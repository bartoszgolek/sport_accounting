<?php
    /**
     * Created by PhpStorm.
     * User: bg
     * Date: 06.04.16
     * Time: 22:12
     */

    namespace AppBundle\Controller\Booking;

    use AppBundle\Entity\Booking\Invoice;
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
                //$em->persist($invoice);
                //$em->flush();

                //return $this->redirectToRoute('booking_book_show', array('id' => $invoice->getId()));
            }

            return $this->render('booking/invoice/new.html.twig', array(
                'invoice' => $invoice,
                'form' => $form->createView(),
            ));
        }
    }