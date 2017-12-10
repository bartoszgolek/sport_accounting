<?php

namespace AppBundle\Controller\User;

use AppBundle\Repository\UserRepository;
use AppBundle\Utils\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/user", service="AppBundle\Controller\User\UserIndexController")
 */
class UserIndexController
{
    /** @var UserRepository */
    private $userRepository;

    /** @var View */
    private $view;

    /**
     * @param UserRepository $userRepository
     * @param View $view
     */
    public function __construct(UserRepository $userRepository, View $view)
    {
        $this->userRepository = $userRepository;
        $this->view = $view;
    }

    /**
     * @Route("/", name="user_index")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->view->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }
}
