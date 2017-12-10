<?php

namespace AppBundle\Utils {

    use Symfony\Bundle\TwigBundle\TwigEngine;
    use Symfony\Component\HttpFoundation\Response;

    class View
    {
        /** @var TwigEngine */
        private $twigEngine;

        /**
         * @param TwigEngine $twigEngine
         */
        public function __construct(TwigEngine $twigEngine)
        {
            $this->twigEngine = $twigEngine;
        }

        /**
         * @param $view
         * @param array $parameters
         *
         * @return Response
         */
        public function render($view, array $parameters = []): Response
        {
            $content = $this->twigEngine->render($view, $parameters);

            $response = new Response();
            $response->setContent($content);

            return $response;
        }
    }
}