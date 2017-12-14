<?php

namespace AppBundle\Controller\Tag;

use AppBundle\Form\TagType;
use AppBundle\Repository\TagRepository;
use AppBundle\Utils\Form;
use AppBundle\Utils\Redirect;
use AppBundle\Utils\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Tag;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/tag", service="AppBundle\Controller\Tag\EditTagController")
 */
class EditTagController
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
     * @Route("/{id}/edit", name="tag_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Tag $tag
     *
     * @return Response
     */
    public function editAction(Request $request, Tag $tag): Response
    {
        $deleteForm = $this->form->createDelete('tag_delete', $tag->getId());
        $editForm = $this->form->create(TagType::class, $request, $tag);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->tagRepository->save($tag);

            return $this->redirect->toRoute('tag_edit', ['id' => $tag->getId()]);
        }

        return $this->view->render('tag/edit.html.twig', [
            'tag' => $tag,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }
}
