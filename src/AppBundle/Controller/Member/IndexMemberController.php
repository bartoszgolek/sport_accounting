<?php

namespace AppBundle\Controller\Member;

use AppBundle\Repository\MemberRepository;
use AppBundle\Utils\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/member", service="AppBundle\Controller\Member\IndexMemberController")
 */
class IndexMemberController
{
    /** @var MemberRepository */
    private $memberRepository;

    /** @var View */
    private $view;

    /**
     * @param MemberRepository $memberRepository
     * @param View $view
     */
    public function __construct(MemberRepository $memberRepository, View $view)
    {
        $this->memberRepository = $memberRepository;
        $this->view = $view;
    }

    /**
     * @Route("/", name="member_index")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        $members = $this->memberRepository->findAll();

        return $this->view->render('member/index.html.twig', [
            'members' => $members,
        ]);
    }
}
