<?php

namespace AppBundle\Controller\Documents;

use AppBundle\Entity\Documents\JournalPosition;
use AppBundle\Form\Booking\BookTypes;
use AppBundle\Form\Documents\JournalType;
use AppBundle\Form\Documents\JournalTypes;
use AppBundle\Repository\Documents\JournalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Documents\Journal;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/journal", service="AppBundle\Controller\Documents\IndexJournalController")
 */
class IndexJournalController extends Controller
{
    /** @var JournalRepository */
    private $journalRepository;

    /**
     * @param JournalRepository $journalRepository
     */
    public function __construct(JournalRepository $journalRepository)
    {
        $this->journalRepository = $journalRepository;
    }

    /**
     * @Route("/", name="documents_journal_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $journals = $this->journalRepository->findAll();

        return $this->render('documents/journal/index.html.twig', [
            'documents_journals' => $journals,
            'book_types' => BookTypes::getArray(),
            'journal_types' => JournalTypes::getArray()
        ]);
    }
}
