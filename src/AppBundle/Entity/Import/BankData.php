<?php
namespace AppBundle\Entity\Import;


class BankData
{
    /** @var  int */
    private $bank_account_column;

    /** @var  int */
    private $commit_date_column;

    /**
     * @var int
     */
    private $transaction_date_column;

    /**
     * @var int
     */
    private $title_column;

    /**
     * @var  int
     */
    private $account_name_column;

    /**
     * @var  int
     */
    private $account_number_column;

    /**
     * @var  int
     */
    private $amount_column;

    /** @var string */
    private $journal_description_template = '{Title}, {Account}';

    /**
     * @return int
     */
    public function getCommitDateColumn()
    {
        return $this->commit_date_column;
    }

    /**
     * @param int $commit_date_column
     */
    public function setCommitDateColumn($commit_date_column)
    {
        $this->commit_date_column = $commit_date_column;
    }

    /**
     * @return int
     */
    public function getTransactionDateColumn()
    {
        return $this->transaction_date_column;
    }

    /**
     * @param int $transaction_date_column
     */
    public function setTransactionDateColumn($transaction_date_column)
    {
        $this->transaction_date_column = $transaction_date_column;
    }

    /**
     * @return int
     */
    public function getTitleColumn()
    {
        return $this->title_column;
    }

    /**
     * @param int $title_column
     */
    public function setTitleColumn($title_column)
    {
        $this->title_column = $title_column;
    }

    /**
     * @return int
     */
    public function getAccountNameColumn()
    {
        return $this->account_name_column;
    }

    /**
     * @param int $account_name_column
     */
    public function setAccountNameColumn($account_name_column)
    {
        $this->account_name_column = $account_name_column;
    }

    /**
     * @return int
     */
    public function getAccountNumberColumn()
    {
        return $this->account_number_column;
    }

    /**
     * @param int $account_number_column
     */
    public function setAccountNumberColumn($account_number_column)
    {
        $this->account_number_column = $account_number_column;
    }

    /**
     * @return int
     */
    public function getAmountColumn()
    {
        return $this->amount_column;
    }

    /**
     * @param int $amount_column
     */
    public function setAmountColumn($amount_column)
    {
        $this->amount_column = $amount_column;
    }

    /**
     * @return int
     */
    public function getBankAccountColumn()
    {
        return $this->bank_account_column;
    }

    /**
     * @param int $bank_account_column
     */
    public function setBankAccountColumn($bank_account_column)
    {
        $this->bank_account_column = $bank_account_column;
    }

    /**
     * @return string
     */
    public function getJournalDescriptionTemplate()
    {
        return $this->journal_description_template;
    }

    /**
     * @param string $journal_description_template
     */
    public function setJournalDescriptionTemplate($journal_description_template)
    {
        $this->journal_description_template = $journal_description_template;
    }
}