<?php

namespace AppBundle\Controller\Member;

use AppBundle\Repository\MemberRepository;
use AppBundle\Utils\Form;
use AppBundle\Utils\Redirect;
use AppBundle\Utils\View;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Member;
use AppBundle\Form\MemberType;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/member", service="AppBundle\Controller\Member\NewMemberController")
 */
class NewMemberController
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
     * @Route("/new", name="member_new")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function newAction(Request $request): Response
    {
        $member = new Member();
        $form = $this->form->create(MemberType::class, $request, $member);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->memberRepository->save($member);

            return $this->redirect->toRoute('member_show', ['id' => $member->getId()]);
        }

        return $this->view->render('member/new.html.twig', [
            'member' => $member,
            'form' => $form->createView(),
        ]);
    }
}
