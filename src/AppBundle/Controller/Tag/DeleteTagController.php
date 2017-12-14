<?php

namespace AppBundle\Controller\Tag;

use AppBundle\Repository\TagRepository;
use AppBundle\Utils\Form;
use AppBundle\Utils\Redirect;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Tag;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/tag", service="AppBundle\Controller\Tag\DeleteTagController")
 */
class DeleteTagController
{
    /** @var TagRepository */
    private $tagRepository;

    /** @var Redirect */
    private $redirect;

    /** @var Form */
    private $form;

    /**
     * @param TagRepository $tagRepository
     * @param Redirect $redirect
     * @param Form $form
     */
    public function __construct(TagRepository $tagRepository, Redirect $redirect, Form $form)
    {
        $this->tagRepository = $tagRepository;
        $this->redirect = $redirect;
        $this->form = $form;
    }


    /**
     * @Route("/{id}", name="tag_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Tag $tag
     *
     * @return Response
     */
    public function deleteAction(Request $request, Tag $tag): Response
    {
        $form = $this->form->createDelete('tag_delete', $tag->getId());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagRepository->delete($tag);
        }

        return $this->redirect->toRoute('tag_index');
    }
}
