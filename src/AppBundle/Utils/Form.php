<?php

namespace AppBundle\Utils {

    use Symfony\Bundle\FrameworkBundle\Routing\Router;
    use Symfony\Component\Form\Extension\Core\Type\FormType;
    use Symfony\Component\Form\FormFactory;
    use Symfony\Component\Form\FormInterface;
    use Symfony\Component\HttpFoundation\Request;

    class Form
    {
        /** @var FormFactory */
        private $formFactory;

        /** @var Router */
        private $router;

        /**
         * @param FormFactory $formFactory
         * @param Router $router
         */
        public function __construct(FormFactory $formFactory, Router $router)
        {
            $this->formFactory = $formFactory;
            $this->router = $router;
        }

        /**
         * @param string $type
         * @param Request $request
         * @param $data
         * @param array $options
         *
         * @return FormInterface
         */
        public function create(string $type, Request $request, $data, $options = []): FormInterface
        {
            $form = $this->createEmpty($type, $data, $options);
            $form->handleRequest($request);

            return $form;
        }

        /**
         * @param string $type
         * @param $data
         * @param array $options
         *
         * @return FormInterface
         */
        public function createEmpty(string $type, $data, $options = []): FormInterface
        {
            return $this->formFactory->create($type, $data, $options);
        }

        /**
         * @param string $route
         * @param mixed $id
         *
         * @return FormInterface
         */
        public function createDelete($route, $id): FormInterface
        {
            return $this->formFactory->createBuilder(FormType::class, null, [])
                ->setAction($this->router->generate($route, ['id' => $id]))
                ->setMethod('DELETE')
                ->getForm();
        }
    }
}