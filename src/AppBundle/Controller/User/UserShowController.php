<?php

namespace AppBundle\Controller\User;

use AppBundle\Utils\Form;
use AppBundle\Utils\View;
use Symfony\Component\Form\FormInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/user", service="AppBundle\Controller\User\UserShowController")
 */
class UserShowController
{
    /** @var View */
    private $view;

    /** @var Form */
    private $form;

    /**
     * @param View $view
     * @param Form $form
     */
    public function __construct(View $view, Form $form)
    {
        $this->view = $view;
        $this->form = $form;
    }

    /**
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     *
     * @param User $user
     *
     * @return Response
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->view->render('user/show.html.twig', [
            'user' => $user,
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
