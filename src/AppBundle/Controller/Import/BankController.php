<?php

namespace AppBundle\Controller\Import;

use AppBundle\Entity\Import\CsvFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Session;

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
    const HAS_HEADER_REW = 'hasHeaderRow';

    /**
     * @Route("/", name="import_bank")
     * @Method({"GET", "POST"})
     */
    public function step1Action(Request $request)
    {
        $csvFile = new CsvFile();
        $csvFileForm = $this->createForm('AppBundle\Form\Import\CsvFileType', $csvFile);
        $csvFileForm->handleRequest($request);

        if ($csvFileForm->isSubmitted() && $csvFileForm->isValid()) {
            /** @var UploadedFile $file */
            $file = $csvFile->getFile();

            /** @var Session $s */
            $s = $this->container->get('session');

            $temp_dir = sys_get_temp_dir();
            $temp_file = tempnam($temp_dir, $file->getFilename());

            copy($temp_dir.DIRECTORY_SEPARATOR.$file->getFilename(), $temp_file);

            $s->set(self::BANK_IMPORT_CSV_SESSION_FIELD,
                [
                    self::FILE_NAME => $temp_file,
                    self::FIELD_SEPARATOR => $csvFile->getFieldSeparator(),
                    self::LINE_SEPARATOR => $csvFile->getLineSeparator(),
                    self::HAS_HEADER_REW => $csvFile->getHasHeaderRow()
                ]);

            return $this->redirectToRoute('import_bank_step2');
        }

        return $this->render('import/bank/step1.html.twig',
            array(
                'choose_form' => $csvFileForm->createView()
            ));
    }

    /**
     * @Route("/step2", name="import_bank_step2")
     * @Method({"GET", "POST"})
     */
    public function step2Action(Request $request)
    {
        /** @var Session $s */
        $s = $this->container->get('session');
        $bankImportInfo = $s->get(self::BANK_IMPORT_CSV_SESSION_FIELD);
        $bankImport = file_get_contents($bankImportInfo[Self::FILE_NAME]);


//        $csvFile = new CsvFile();
//        $csvFileForm = $this->createForm('AppBundle\Form\Import\CsvFileType', $csvFile);
//        $csvFileForm->handleRequest($request);
//
//        if ($csvFileForm->isSubmitted() && $csvFileForm->isValid()) {
//            /** @var UploadedFile $file */
//            $file = $csvFile->getFile();
//            $file_content = file_get_contents($file)
//
//            $temp_dir = sys_get_temp_dir();
//            $temp_file = tempnam($temp_dir, $file->getFilename());
//            $file->move($temp_dir, $file->getFilename());
//
//            return $this->redirectToRoute('import_bank_step2');
//        }

        $delimiter = $bankImportInfo[self::LINE_SEPARATOR];
        $this->addFlash('info', $delimiter);
        return $this->render('import/bank/step2.html.twig',
            array(
                'lines' => explode($delimiter, $bankImport, 10),
                'bank_import' => $bankImport
            ));
    }
}
