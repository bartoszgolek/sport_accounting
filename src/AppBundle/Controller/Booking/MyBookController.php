<?php
    /**
     * Created by PhpStorm.
     * User: bg
     * Date: 06.04.16
     * Time: 22:12
     */

    namespace AppBundle\Controller\Booking;

    use AppBundle\Entity\Booking\Book;
    use AppBundle\Entity\User;
    use AppBundle\Utils\Forwarder;
    use AppBundle\Utils\Notification;
    use AppBundle\Utils\View;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Symfony\Component\BrowserKit\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

    /**
     * @Route("/", service="AppBundle\Controller\Booking\MyBookController")
     */
    class MyBookController
    {
        /** @var TokenStorageInterface */
        private $tokenStorage;

        /** @var Notification */
        private $notification;

        /** @var View */
        private $view;

        /** @var Forwarder */
        private $forwarder;

        /**
         * @param TokenStorageInterface $tokenStorage
         * @param Notification $notification
         * @param View $view
         * @param Forwarder $forwarder
         */
        public function __construct(
            TokenStorageInterface $tokenStorage,
            Notification $notification,
            View $view,
            Forwarder $forwarder)
        {
            $this->tokenStorage = $tokenStorage;
            $this->notification = $notification;
            $this->view = $view;
            $this->forwarder = $forwarder;
        }

        /**
         * @Route("/my_book", name="booking_my_book_show")
         * @Method({"GET", "POST"})
         *
         * @return Response
         */
        public function showAction(): Response
        {
            /** @var User $usr */
            $usr = $this->tokenStorage->getToken()->getUser();

            $member = $usr->getMember();

            if ($member === null) {
                $this->notification->error('User has no attached member!');
                return $this->view->render('booking/my_book/no_book.html.twig');
            }

            /** @var Book $book */
            $book = $member->getBook();

            if ($book === null) {
                $this->notification->error('User member has no attached account!');
                return $this->view->render('booking/my_book/no_book.html.twig');
            }

            return $this->forwarder->forward('AppBundle\Controller\Booking\BookShowController::showAction', ['book' => $book]);
        }
    }