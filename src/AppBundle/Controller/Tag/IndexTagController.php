<?php

namespace AppBundle\Controller\Tag;

use AppBundle\Repository\TagRepository;
use AppBundle\Utils\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/tag", service="AppBundle\Controller\Tag\IndexTagController")
 */
class IndexTagController
{
    /** @var TagRepository */
    private $tagRepository;

    /** @var View */
    private $view;

    /**
     * @param TagRepository $tagRepository
     * @param View $view
     */
    public function __construct(TagRepository $tagRepository, View $view)
    {
        $this->tagRepository = $tagRepository;
        $this->view = $view;
    }

    /**
     * @Route("/", name="tag_index")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        $tags = $this->tagRepository->findAll();

        return $this->view->render('tag/index.html.twig', [
            'tags' => $tags,
        ]);
    }
}
