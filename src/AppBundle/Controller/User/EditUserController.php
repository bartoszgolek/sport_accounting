<?php

namespace AppBundle\Controller\User;

use AppBundle\Form\UserType;
use AppBundle\Repository\UserRepository;
use AppBundle\Utils\Form;
use AppBundle\Utils\Redirect;
use AppBundle\Utils\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/user", service="AppBundle\Controller\User\EditUserController")
 */
class EditUserController
{
    /** @var Form */
    private $form;

    /** @var UserRepository */
    private $userRepository;

    /** @var Redirect */
    private $redirect;

    /** @var View */
    private $view;

    /**
     * EditUserController constructor.
     * @param Form $form
     * @param UserRepository $userRepository
     * @param Redirect $redirect
     * @param View $view
     */
    public function __construct(Form $form, UserRepository $userRepository, Redirect $redirect, View $view)
    {
        $this->form = $form;
        $this->userRepository = $userRepository;
        $this->redirect = $redirect;
        $this->view = $view;
    }

    /**
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param User $user
     *
     * @return Response
     */
    public function editAction(Request $request, User $user): Response
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->form->create(UserType::class, $request, $user);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->userRepository->save($user);

            return $this->redirect->toRoute('user_edit', ['id' => $user->getId()]);
        }

        return $this->view->render('user/edit.html.twig', [
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
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
