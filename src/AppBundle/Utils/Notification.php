<?php

namespace AppBundle\Utils {

    use Symfony\Component\HttpFoundation\Session\Session;

    class Notification
    {
        const SUCCESS = 'success';
        const INFO = 'info';
        const WARNING = 'warning';
        const ERROR = 'error';

        /** @var Session */
        private $session;

        /**
         * @param Session $session
         */
        public function __construct(Session $session)
        {
            $this->session = $session;
        }

        public function success($message) {
            $this->session->getFlashBag()->add(self::SUCCESS, $message);
        }

        public function info($message) {
            $this->session->getFlashBag()->add(self::INFO, $message);
        }

        public function warning($message) {
            $this->session->getFlashBag()->add(self::WARNING, $message);
        }

        public function error($message) {
            $this->session->getFlashBag()->add(self::ERROR, $message);
        }
    }
}