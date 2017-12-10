<?php

namespace AppBundle\Utils {

    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpKernel\HttpKernelInterface;

    class Forwarder
    {
        /** @var RequestStack */
        private $requestStack;

        /** @var HttpKernelInterface */
        private $httpKernel;

        public function __construct(RequestStack $requestStack, HttpKernelInterface $httpKernel)
        {
            $this->requestStack = $requestStack;
            $this->httpKernel = $httpKernel;
        }

        public function forward($controller, array $path = [], array $query = []): Response
        {
            $request = $this->requestStack->getCurrentRequest();
            $path['_forwarded'] = $request->attributes;
            $path['_controller'] = $controller;
            $subRequest = $request->duplicate($query, null, $path);

            return $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        }
    }
}