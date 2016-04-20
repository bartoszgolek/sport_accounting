<?php

namespace AppBundle\Controller\Import;

use AppBundle\Entity\Documents\Journal;
use AppBundle\Entity\Documents\JournalPosition;
use AppBundle\Entity\Import\BankData;
use AppBundle\Entity\LineSeparators;
use AppBundle\Form\Documents\JournalTypes;
use AppBundle\Form\Import\BankDataType;
use AppBundle\Form\Import\CsvFileType;
use AppBundle\Form\Import\UploadFileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
    const ORIG_FILE_NAME = 'orig_file_name';
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
        $csvFileForm = $this->createForm(UploadFileType::class, $uploadFile);
        $csvFileForm->handleRequest($request);

        if ($csvFileForm->isSubmitted() && $csvFileForm->isValid()) {
            /** @var UploadedFile $file */
            $file = $uploadFile->getFile();

            /** @var Session $s */
            $s = $this->container->get('session');

            $temp_dir = sys_get_temp_dir();
            $temp_file = tempnam($temp_dir, $file->getFilename());

            copy($file->getPathname(), $temp_file);

            $s->set(self::BANK_IMPORT_CSV_SESSION_FIELD,
                [
                    self::FILE_NAME => $temp_file,
                    self::ORIG_FILE_NAME => $file->getClientOriginalName(),
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
        $fileName = $bankImportInfo[self::FILE_NAME];
        $originalName = $bankImportInfo[self::ORIG_FILE_NAME];
        $bankImport = file_get_contents($fileName);

        $csvFile = new CsvFile();
        $csvFile->setFileName($fileName);
        $csvFile->setOriginalName($originalName);
        $csvFileForm = $this->createForm(CsvFileType::class, $csvFile);
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
                    self::ORIG_FILE_NAME  => $csvFile->getOriginalName(),
                    self::SKIP => $csvFile->getSkip()
                ]);

            return $this->redirectToRoute('import_bank_step3');
        }

        return $this->render('import/bank/step2.html.twig',
            array(
                'csv_form' => $csvFileForm->createView(),
                'bank_import' => $bankImport
            ));
    }

    /**
     * @Route("/step3", name="import_bank_step3")
     * @Method({"GET", "POST"})
     */
    public function step3Action(Request $request)
    {
        /** @var Session $s */
        $s = $this->container->get('session');
        $bankImportInfo = $s->get(self::BANK_IMPORT_CSV_SESSION_FIELD);
        $bankImport = file_get_contents($bankImportInfo[self::FILE_NAME]);


        $lineDelimiter = LineSeparators::getCode($bankImportInfo[self::LINE_SEPARATOR]);
        $fieldDelimiter = $bankImportInfo[self::FIELD_SEPARATOR];
        $hasHeaderRow = $bankImportInfo[self::HAS_HEADER_ROW];
        $originalName = $bankImportInfo[self::ORIG_FILE_NAME];

        $lines = explode($lineDelimiter, $bankImport);

        $headers = [];
        if ($hasHeaderRow) {
            $headers = explode($fieldDelimiter, array_shift($lines));
        }

        $rows = [];
        for ($i = 0; $i < count($lines); $i++){
            if ($i >= $bankImportInfo[self::SKIP] && strpos($lines[$i], $fieldDelimiter) !== false) {
                $row = explode($fieldDelimiter, $lines[$i]);
                $rows[] = $row;
            }
        }

        if (!$hasHeaderRow && count($rows) > 0) {
            for ($i = 0; $i <  count($rows[0]); $i++) {
                $headers[] = "Column-".($i+1);
            }
        }

        $bankData = new BankData();
        $bankDataForm = $this->createForm(BankDataType::class, $bankData, ['columns' => array_flip($headers)]);
        $bankDataForm->handleRequest($request);

        if ($bankDataForm->isSubmitted() && $bankDataForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $voucher = uniqid("V");

            $journal = new Journal();
            $journal->setDescription("Bank import - "  . $originalName);
            $journal->setType(JournalTypes::BASIC);

            foreach ($rows as $row) {
                $amount = $row[$bankData->getTransactionDate()];
                if ($amount != 0) {
                    $this->createJournalPosition(
                        $journal,
                        $voucher,
                        new \DateTime($row[$bankData->getTransactionDate()]),
                        $this->getJournalDescription($row, $bankData),
                        $bankData->getBankAccount(),
                        $amount > 0 ? $amount : null,
                        $amount < 0 ? $amount : null
                    );
                    $this->createJournalPosition(
                        $journal,
                        $voucher,
                        new \DateTime($row[$bankData->getTransactionDate()]),
                        $this->getJournalDescription($row, $bankData),
                        null,
                        $amount < 0 ? $amount : null,
                        $amount > 0 ? $amount : null
                    );
                }
            }

            $em->persist($journal);
            $em->flush();

            return $this->redirectToRoute('documents_journal_edit', array('id' => $journal->getId()));
        }


        return $this->render('import/bank/step3.html.twig',
            array(
                'lines' => explode($lineDelimiter, $bankImport, 10),
                'bank_import' => $bankImport,
                'headers' => $headers,
                'rows' => $rows,
                'bank_data_form' => $bankDataForm->createView()
            ));
    }

    /**
     * @param Journal $journal
     * @param $voucher
     * @param \DateTime $date
     * @param $description
     * @param $book
     * @param $credit
     * @param $debit
     */
    protected function createJournalPosition(Journal $journal, $voucher, \DateTime $date, $description, $book, $debit, $credit)
    {
        $pos = new JournalPosition();
        $pos->setVoucher($voucher)
            ->setDate($date)
            ->setDescription($description)
            ->setBook($book)
            ->setCredit($credit)
            ->setDebit($debit);
        $journal->addPosition($pos);
    }

    /**
     * @param $fields
     * @param $bankData
     *
     * @return mixed
     */
    protected function getJournalDescription(array $fields, BankData $bankData)
    {
        $description = $bankData->getJournalDescriptionTemplate();
        $description = str_replace("{CommitDate}", $fields[$bankData->getCommitDate()], $description);
        $description = str_replace("{TransactionDate}", $fields[$bankData->getTransactionDate()], $description);
        $description = str_replace("{Title}", $fields[$bankData->getTitle()], $description);
        $description = str_replace("{Account}", $fields[$bankData->getAccount()], $description);
        $description = str_replace("{AccountNumber}", $fields[$bankData->getAccountNumber()], $description);
        $description = str_replace("{Amount}", $fields[$bankData->getAmount()], $description);

        return $description;
    }
}
