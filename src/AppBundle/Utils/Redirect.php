<?php

namespace AppBundle\Utils {

    use Symfony\Bundle\FrameworkBundle\Routing\Router;
    use Symfony\Bundle\TwigBundle\TwigEngine;
    use Symfony\Component\HttpFoundation\RedirectResponse;

    class Redirect
    {
        /** @var Router */
        private $router;

        /**
         * @param Router $router
         */
        public function __construct(Router $router)
        {
            $this->router = $router;
        }


        /**
         * @param string $route
         * @param array $parameters
         * @param int $status
         *
         * @return RedirectResponse
         */
        public function toRoute($route, array $parameters = [], $status = 302): RedirectResponse
        {
            return $this->toUrl(
                $this->router->generate($route, $parameters),
                $status
            );
        }

        /**
         * @param string $url
         * @param int $status
         *
         * @return RedirectResponse
         */
        public function toUrl($url, $status = 302): RedirectResponse
        {
            return new RedirectResponse($url, $status);
        }
    }
}