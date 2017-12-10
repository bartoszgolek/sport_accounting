<?php

namespace AppBundle\Controller\Member;

use AppBundle\Form\MemberType;
use AppBundle\Repository\MemberRepository;
use AppBundle\Utils\Form;
use AppBundle\Utils\Redirect;
use AppBundle\Utils\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Member;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/member", service="AppBundle\Controller\Member\EditMemberController")
 */
class EditMemberController
{
    /** @var Form */
    private $form;

    /** @var MemberRepository */
    private $memberRepository;

    /** @var Redirect */
    private $redirect;

    /** @var View */
    private $view;

    /**
     * @param Form $form
     * @param MemberRepository $memberRepository
     * @param Redirect $redirect
     * @param View $view
     */
    public function __construct(Form $form, MemberRepository $memberRepository, Redirect $redirect, View $view)
    {
        $this->form = $form;
        $this->memberRepository = $memberRepository;
        $this->redirect = $redirect;
        $this->view = $view;
    }

    /**
     * @Route("/{id}/edit", name="member_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Member $member
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, Member $member)
    {
        $deleteForm = $this->createDeleteForm($member);
        $editForm = $this->form->create(MemberType::class, $request, $member);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->memberRepository->save($member);

            return $this->redirect->toRoute('member_edit', ['id' => $member->getId()]);
        }

        return $this->view->render('member/edit.html.twig', [
            'member' => $member,
            'edit_form' => $editForm->createView(),
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
