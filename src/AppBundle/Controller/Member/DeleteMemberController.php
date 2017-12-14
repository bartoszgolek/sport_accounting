<?php

namespace AppBundle\Controller\Member;

use AppBundle\Repository\MemberRepository;
use AppBundle\Utils\Form;
use AppBundle\Utils\Redirect;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Member;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/member", service="AppBundle\Controller\Member\DeleteMemberController")
 */
class DeleteMemberController
{
    /** @var Form */
    private $form;

    /** @var MemberRepository */
    private $memberRepository;

    /** @var Redirect */
    private $redirect;

    /**
     * @param Form $form
     * @param MemberRepository $memberRepository
     * @param Redirect $redirect
     */
    public function __construct(Form $form, MemberRepository $memberRepository, Redirect $redirect)
    {
        $this->form = $form;
        $this->memberRepository = $memberRepository;
        $this->redirect = $redirect;
    }

    /**
     * @Route("/{id}", name="member_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Member $member
     *
     * @return Response
     */
    public function deleteAction(Request $request, Member $member): Response
    {
        $form = $this->form->createDelete('member_delete', $member->getId());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->memberRepository->delete($member);
        }

        return $this->redirect->toRoute('member_index');
    }
}
