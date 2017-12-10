<?php

namespace AppBundle\Controller\Import;

use AppBundle\Entity\Documents\Journal;
use AppBundle\Entity\Documents\JournalPosition;
use AppBundle\Entity\Import\BankData;
use AppBundle\Entity\LineSeparators;
use AppBundle\Form\Documents\JournalTypes;
use AppBundle\Form\Import\BankDataType;
use AppBundle\Repository\Documents\JournalRepository;
use AppBundle\Utils\Form;
use AppBundle\Utils\Redirect;
use AppBundle\Utils\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * User controller.
 *
 * @Route("/import/bank", service="AppBundle\Controller\Import\ColumnMappingBankController")
 */
class ColumnMappingBankController
{
    /** @var Form */
    private $form;

    /** @var Session */
    private $session;

    /** @var Redirect */
    private $redirect;

    /** @var View */
    private $view;

    /** @var JournalRepository */
    private $journalRepository;

    /**
     * @param JournalRepository $journalRepository
     * @param Form $form
     * @param Session $session
     * @param Redirect $redirect
     * @param View $view
     */
    public function __construct(JournalRepository $journalRepository, Form $form, Session $session, Redirect $redirect, View $view)
    {
        $this->journalRepository = $journalRepository;
        $this->form = $form;
        $this->session = $session;
        $this->redirect = $redirect;
        $this->view = $view;
    }

    /**
     * @Route("/import_bank_column_mapping", name="import_bank_column_mapping")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function importBankColumnMappingAction(Request $request): Response
    {
        $bankImportInfo = $this->session->get(FileUploadController::CSV_SESSION_FIELD);
        $bankImport = file_get_contents($bankImportInfo[FileUploadController::FILE_NAME]);


        $lineDelimiter = LineSeparators::getCode($bankImportInfo[FileConfigurationController::LINE_SEPARATOR]);
        $fieldDelimiter = $bankImportInfo[FileConfigurationController::FIELD_SEPARATOR];
        $hasHeaderRow = $bankImportInfo[FileConfigurationController::HAS_HEADER_ROW];
        $originalName = $bankImportInfo[FileUploadController::ORIG_FILE_NAME];

        $lines = explode($lineDelimiter, $bankImport);

        $i = 0;
        while ($i < count($lines) && $i < $bankImportInfo[FileConfigurationController::SKIP]){
            array_shift($lines);
            $i++;
        }

        $headers = [];
        if ($hasHeaderRow) {
            $headers = explode($fieldDelimiter, array_shift($lines));
        } else if (count($lines) > 0) {
            for ($i = 0; $i <  count($lines[0]); $i++) {
                $headers[] = "Column-".($i+1);
            }
        }

        $bankData = new BankData();
        $bankData->setCommitDateColumn(0);
        $bankData->setTransactionDateColumn(1);
        $bankData->setTitleColumn(2);
        $bankData->setAccountNameColumn(3);
        $bankData->setAccountNumberColumn(4);
        $bankData->setAmountColumn(5);
        $bankDataForm = $this->form->create(BankDataType::class, $request, $bankData, ['columns' => array_flip($headers)]);

        if ($bankDataForm->isSubmitted() && $bankDataForm->isValid()) {

            $journal = new Journal();
            $journal->setDescription("Bank import - "  . $originalName);
            $journal->setType(JournalTypes::BASIC);

            foreach ($lines as $line) {
                $amount = $this->toFloat($line[$bankData->getAmountColumn()]);
                if ($amount != 0) {
                    $voucher = uniqid("V");
                    $this->createJournalPosition(
                        $journal,
                        $voucher,
                        new \DateTime($line[$bankData->getTransactionDateColumn()]),
                        $this->getJournalDescription($line, $bankData),
                        $bankData->getBankAccountColumn(),
                        $amount > 0 ? $amount : null,
                        $amount < 0 ? $amount : null
                    );
                    $this->createJournalPosition(
                        $journal,
                        $voucher,
                        new \DateTime($line[$bankData->getTransactionDateColumn()]),
                        $this->getJournalDescription($line, $bankData),
                        null,
                        $amount < 0 ? $amount : null,
                        $amount > 0 ? $amount : null
                    );
                }
            }

            $this->journalRepository->save($journal);

            return $this->redirect->toRoute('documents_journal_edit', ['id' => $journal->getId()]);
        }


        return $this->view->render('import/bank/importBankColumnMapping.html.twig',
            [
                'lines' => explode($lineDelimiter, $bankImport, 10),
                'bank_import' => $bankImport,
                'headers' => $headers,
                'rows' => $lines,
                'bank_data_form' => $bankDataForm->createView()
            ]);
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
        $description = str_replace("{CommitDate}", $fields[$bankData->getCommitDateColumn()], $description);
        $description = str_replace("{TransactionDate}", $fields[$bankData->getTransactionDateColumn()], $description);
        $description = str_replace("{Title}", $fields[$bankData->getTitleColumn()], $description);
        $description = str_replace("{Account}", $fields[$bankData->getAccountNameColumn()], $description);
        $description = str_replace("{AccountNumber}", $fields[$bankData->getAccountNumberColumn()], $description);
        $description = str_replace("{Amount}", $fields[$bankData->getAmountColumn()], $description);

        return $description;
    }

    function toFloat($number) {
        $dotPos = strrpos($number, '.');
        $commaPos = strrpos($number, ',');
        $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
            ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

        if (!$sep) {
            return floatval(preg_replace("/[^0-9]/", "", $number));
        }

        return floatval(
            preg_replace("/[^0-9]/", "", substr($number, 0, $sep)) . '.' .
            preg_replace("/[^0-9]/", "", substr($number, $sep + 1, strlen($number)))
        );
    }
}
