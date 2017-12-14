<?php

namespace AppBundle\Controller\Tag;

use AppBundle\Utils\Form;
use AppBundle\Utils\View;
use Symfony\Component\Form\FormInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Tag;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/tag", service="AppBundle\Controller\Tag\ShowTagShowController")
 */
class ShowTagShowController
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
     * @Route("/{id}", name="tag_show")
     * @Method("GET")
     *
     * @param Tag $tag
     *
     * @return Response
     */
    public function showAction(Tag $tag)
    {
        $deleteForm = $this->form->createDelete('tag_delete', $tag->getId());

        return $this->view->render('tag/show.html.twig', [
            'tag' => $tag,
            'delete_form' => $deleteForm->createView(),
        ]);
    }
}
