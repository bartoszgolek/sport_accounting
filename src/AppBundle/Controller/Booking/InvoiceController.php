<?php
    /**
     * Created by PhpStorm.
     * User: bg
     * Date: 06.04.16
     * Time: 22:12
     */

    namespace AppBundle\Controller\Booking;

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
         * @Route("/booking/invoice/create", name="booking_invoice_create")
         * @Method("GET")
         */
        public function createAction()
        {
            $invoice = new Invoice();
            $form = $this->createForm('AppBundle\Form\Booking\BookType', $booking_book);
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
    }