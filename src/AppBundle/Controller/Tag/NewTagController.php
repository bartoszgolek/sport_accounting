<?php

namespace AppBundle\Controller\Tag;

use AppBundle\Repository\TagRepository;
use AppBundle\Utils\Form;
use AppBundle\Utils\Redirect;
use AppBundle\Utils\View;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Tag;
use AppBundle\Form\TagType;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/tag", service="AppBundle\Controller\Tag\NewTagController")
 */
class NewTagController
{
    /** @var Form */
    private $form;

    /** @var TagRepository */
    private $tagRepository;

    /** @var Redirect */
    private $redirect;

    /** @var View */
    private $view;

    /**
     * @param Form $form
     * @param TagRepository $tagRepository
     * @param Redirect $redirect
     * @param View $view
     */
    public function __construct(Form $form, TagRepository $tagRepository, Redirect $redirect, View $view)
    {
        $this->form = $form;
        $this->tagRepository = $tagRepository;
        $this->redirect = $redirect;
        $this->view = $view;
    }

    /**
     * @Route("/new", name="tag_new")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function newAction(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->form->create(TagType::class, $request, $tag);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagRepository->save($tag);

            return $this->redirect->toRoute('tag_show', ['id' => $tag->getId()]);
        }

        return $this->view->render('tag/new.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }
}
