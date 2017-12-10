<?php

namespace AppBundle\Controller\Import;

use AppBundle\Form\Import\UploadFileType;
use AppBundle\Utils\Form;
use AppBundle\Utils\Redirect;
use AppBundle\Utils\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Import\UploadFile;

/**
 * @Route("/import/bank", service="AppBundle\Controller\Import\FileUploadController")
 */
class FileUploadController
{
    const CSV_SESSION_FIELD = 'bank_import_csv';
    const FILE_NAME = 'file_name';
    const ORIG_FILE_NAME = 'orig_file_name';

    /** @var Form */
    private $form;

    /** @var Session */
    private $session;

    /** @var Redirect */
    private $redirect;

    /** @var View */
    private $view;

    /**
     * FileUploadController constructor.
     * @param Form $form
     * @param Session $session
     * @param Redirect $redirect
     * @param View $view
     */
    public function __construct(Form $form, Session $session, Redirect $redirect, View $view)
    {
        $this->form = $form;
        $this->session = $session;
        $this->redirect = $redirect;
        $this->view = $view;
    }

    /**
     * @Route("/", name="upload_csv_file")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function uploadCsvFileAction(Request $request): Response
    {
        $uploadFile = new UploadFile();
        $csvFileForm = $this->form->create(UploadFileType::class, $request, $uploadFile);

        if ($csvFileForm->isSubmitted() && $csvFileForm->isValid()) {
            /** @var UploadedFile $file */
            $file = $uploadFile->getFile();

            $temp_dir = sys_get_temp_dir();
            $temp_file = tempnam($temp_dir, $file->getFilename());

            copy($file->getPathname(), $temp_file);

            $this->session->set(self::CSV_SESSION_FIELD,
                [
                    self::FILE_NAME => $temp_file,
                    self::ORIG_FILE_NAME => $file->getClientOriginalName(),
                ]);

            return $this->redirect->toRoute('configure_csv_file');
        }

        return $this->view->render('import/bank/uploadCsvFile.html.twig',
            [
                'choose_form' => $csvFileForm->createView()
            ]);
    }
}
