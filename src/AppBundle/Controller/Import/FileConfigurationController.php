<?php

namespace AppBundle\Controller\Import;

use AppBundle\Form\Import\CsvFileType;
use AppBundle\Utils\Form;
use AppBundle\Utils\Redirect;
use AppBundle\Utils\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Import\CsvFile;

/**
 * User controller.
 *
 * @Route("/import/bank", service="AppBundle\Controller\Import\FileConfigurationController")
 */
class FileConfigurationController
{
    const FIELD_SEPARATOR = 'field_separator';
    const LINE_SEPARATOR = 'line_separator';
    const HAS_HEADER_ROW = 'hasHeaderRow';
    const SKIP = 'skip';

    /** @var Form */
    private $form;

    /** @var Session */
    private $session;

    /** @var Redirect */
    private $redirect;

    /** @var View */
    private $view;

    /**
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
     * @Route("/configure_csv_file", name="configure_csv_file")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function configureCsvFileAction(Request $request): Response
    {
        $bankImportInfo = $this->session->get(FileUploadController::CSV_SESSION_FIELD);
        $fileName = $bankImportInfo[FileUploadController::FILE_NAME];
        $originalName = $bankImportInfo[FileUploadController::ORIG_FILE_NAME];
        $bankImport = file_get_contents($fileName);

        $csvFile = new CsvFile();
        $csvFile->setFileName($fileName);
        $csvFile->setOriginalName($originalName);
        $csvFileForm = $this->form->create(CsvFileType::class, $request, $csvFile);

        if ($csvFileForm->isSubmitted() && $csvFileForm->isValid()) {
            /** @var CsvFile $file */
            $fileName = $csvFile->getFileName();

            $this->session->set(FileUploadController::CSV_SESSION_FIELD,
                [
                    FileUploadController::FILE_NAME       => $fileName,
                    FileUploadController::ORIG_FILE_NAME  => $csvFile->getOriginalName(),
                    self::FIELD_SEPARATOR => $csvFile->getFieldSeparator(),
                    self::LINE_SEPARATOR  => $csvFile->getLineSeparator(),
                    self::HAS_HEADER_ROW  => $csvFile->getHasHeaderRow(),
                    self::SKIP => $csvFile->getSkip()
                ]);

            return $this->redirect->toRoute('import_bank_column_mapping');
        }

        return $this->view->render('import/bank/csvFileConfiguration.html.twig',
            [
                'csv_form' => $csvFileForm->createView(),
                'bank_import' => $bankImport
            ]);
    }
}
