<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Player;
use AppBundle\Form\PlayerType;

/**
 * Player controller.
 *
 * @Route("/player")
 */
class PlayerController extends Controller
{
    /**
     * Lists all Player entities.
     *
     * @Route("/", name="player_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $players = $em->getRepository('AppBundle:Player')->findAll();

        return $this->render('player/index.html.twig', array(
            'players' => $players,
        ));
    }

    /**
     * Creates a new Player entity.
     *
     * @Route("/new", name="player_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $player = new Player();
        $form = $this->createForm('AppBundle\Form\PlayerType', $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($player);
            $em->flush();

            return $this->redirectToRoute('player_show', array('id' => $player->getId()));
        }

        return $this->render('player/new.html.twig', array(
            'player' => $player,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Player entity.
     *
     * @Route("/{id}", name="player_show")
     * @Method("GET")
     */
    public function showAction(Player $player)
    {
        $deleteForm = $this->createDeleteForm($player);

        return $this->render('player/show.html.twig', array(
            'player' => $player,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Player entity.
     *
     * @Route("/{id}/edit", name="player_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Player $player)
    {
        $deleteForm = $this->createDeleteForm($player);
        $editForm = $this->createForm('AppBundle\Form\PlayerType', $player);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($player);
            $em->flush();

            return $this->redirectToRoute('player_edit', array('id' => $player->getId()));
        }

        return $this->render('player/edit.html.twig', array(
            'player' => $player,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Player entity.
     *
     * @Route("/{id}", name="player_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Player $player)
    {
        $form = $this->createDeleteForm($player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($player);
            $em->flush();
        }

        return $this->redirectToRoute('player_index');
    }

    /**
     * Creates a form to delete a Player entity.
     *
     * @param Player $player The Player entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Player $player)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('player_delete', array('id' => $player->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
