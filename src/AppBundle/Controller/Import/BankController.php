<?php

namespace AppBundle\Controller\Import;

use AppBundle\Entity\LineSeparators;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Import\UploadFile;
use AppBundle\Entity\Import\CsvFile;

/**
 * User controller.
 *
 * @Route("/import/bank")
 */
class BankController extends Controller
{
    const BANK_IMPORT_CSV_SESSION_FIELD = 'bank_import_csv';
    const FILE_NAME = 'file_name';
    const FIELD_SEPARATOR = 'field_separator';
    const LINE_SEPARATOR = 'line_separator';
    const HAS_HEADER_ROW = 'hasHeaderRow';
    const SKIP = 'skip';

    /**
     * @Route("/", name="import_bank")
     * @Method({"GET", "POST"})
     */
    public function step1Action(Request $request)
    {
        $uploadFile = new UploadFile();
        $csvFileForm = $this->createForm('AppBundle\Form\Import\UploadFileType', $uploadFile);
        $csvFileForm->handleRequest($request);

        if ($csvFileForm->isSubmitted() && $csvFileForm->isValid()) {
            /** @var UploadedFile $file */
            $file = $uploadFile->getFile();

            /** @var Session $s */
            $s = $this->container->get('session');

            $temp_dir = sys_get_temp_dir();
            $temp_file = tempnam($temp_dir, $file->getFilename());

            copy($temp_dir.DIRECTORY_SEPARATOR.$file->getFilename(), $temp_file);

            $s->set(self::BANK_IMPORT_CSV_SESSION_FIELD,
                [
                    self::FILE_NAME => $temp_file
                ]);

            return $this->redirectToRoute('import_bank_step2');
        }

        return $this->render('import/bank/step1.html.twig',
            array(
                'choose_form' => $csvFileForm->createView()
            ));
    }

    /**
     * @Route("/step2", name="import_bank")
     * @Method({"GET", "POST"})
     */
    public function step2Action(Request $request)
    {
        /** @var Session $s */
        $s = $this->container->get('session');
        $bankImportInfo = $s->get(self::BANK_IMPORT_CSV_SESSION_FIELD);
        $fileName = $bankImportInfo[Self::FILE_NAME];
        $bankImport = file_get_contents($fileName);

        $csvFile = new CsvFile();
        $csvFile->setFileName($fileName);
        $csvFileForm = $this->createForm('AppBundle\Form\Import\CsvFileType', $csvFile);
        $csvFileForm->handleRequest($request);

        if ($csvFileForm->isSubmitted() && $csvFileForm->isValid()) {
            /** @var CsvFile $file */
            $fileName = $csvFile->getFileName();

            $s->set(self::BANK_IMPORT_CSV_SESSION_FIELD,
                [
                    self::FILE_NAME       => $fileName,
                    self::FIELD_SEPARATOR => $csvFile->getFieldSeparator(),
                    self::LINE_SEPARATOR  => $csvFile->getLineSeparator(),
                    self::HAS_HEADER_ROW  => $csvFile->getHasHeaderRow(),
                    self::SKIP => $csvFile->getSkip()
                ]);

            return $this->redirectToRoute('import_bank_step2');
        }

        return $this->render('import/bank/step2.html.twig',
            array(
                'csv_form' => $csvFileForm->createView(),
                'bank_import' => $bankImport
            ));
    }

    /**
     * @Route("/step3", name="import_bank_step2")
     * @Method({"GET", "POST"})
     */
    public function step3Action(Request $request)
    {
        /** @var Session $s */
        $s = $this->container->get('session');
        $bankImportInfo = $s->get(self::BANK_IMPORT_CSV_SESSION_FIELD);
        $bankImport = file_get_contents($bankImportInfo[Self::FILE_NAME]);


        $lineDelimiter = LineSeparators::getCode($bankImportInfo[self::LINE_SEPARATOR]);
        $fieldDelimiter = LineSeparators::getCode($bankImportInfo[self::FIELD_SEPARATOR]);
        $hasHeaderRow = $bankImportInfo[self::HAS_HEADER_ROW];

        $lines = explode($lineDelimiter, $bankImport);

        $header = null;
        if ($hasHeaderRow) {
            $header = explode($fieldDelimiter, array_shift($lines));
        }

        $rows = [];
        for ($i = 0; $i <= count($lines); $i++){
            if ($i < $bankImportInfo[self::SKIP])
            $rows[] = explode($fieldDelimiter, $lines[$i]);
        }

        return $this->render('import/bank/step2.html.twig',
            array(
                'lines' => explode($lineDelimiter, $bankImport, 10),
                'bank_import' => $bankImport,
                'header' => $header,
                'has_header_row' => $hasHeaderRow,
                'rows' => $rows
            ));
    }
}
