<?php

namespace AppBundle\Controller\User;

use AppBundle\Repository\UserRepository;
use AppBundle\Utils\Form;
use AppBundle\Utils\Redirect;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/user", service="AppBundle\Controller\User\DeleteUserController")
 */
class DeleteUserController
{
    /** @var UserRepository */
    private $userRepository;

    /** @var Redirect */
    private $redirect;

    /** @var Form */
    private $form;

    /**
     * DeleteUserController constructor.
     * @param UserRepository $userRepository
     * @param Redirect $redirect
     * @param Form $form
     */
    public function __construct(UserRepository $userRepository, Redirect $redirect, Form $form)
    {
        $this->userRepository = $userRepository;
        $this->redirect = $redirect;
        $this->form = $form;
    }


    /**
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param User $user
     *
     * @return Response
     */
    public function deleteAction(Request $request, User $user): Response
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->delete($user);
        }

        return $this->redirect->toRoute('user_index');
    }

    /**
     * @param User $user
     *
     * @return FormInterface
     */
    private function createDeleteForm(User $user): FormInterface
    {
        return $this->form->createDelete('user_delete', $user->getId());
    }
}
