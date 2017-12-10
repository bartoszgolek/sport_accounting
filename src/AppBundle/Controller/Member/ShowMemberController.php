<?php

namespace AppBundle\Controller\Member;

use AppBundle\Utils\Form;
use AppBundle\Utils\View;
use Symfony\Component\Form\FormInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Member;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/member", service="AppBundle\Controller\Member\ShowMemberController")
 */
class ShowMemberController
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
     * @Route("/{id}", name="member_show")
     * @Method("GET")
     *
     * @param Member $member
     *
     * @return Response
     */
    public function showAction(Member $member): Response
    {
        $deleteForm = $this->createDeleteForm($member);

        return $this->view->render('member/show.html.twig', [
            'member' => $member,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @param Member $member
     *
     * @return FormInterface
     */
    private function createDeleteForm(Member $member): FormInterface
    {
        return $this->form->createDelete('member_delete', $member->getId());
    }
}
