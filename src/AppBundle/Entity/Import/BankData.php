<?php
/**
 * Created by PhpStorm.
 * User: bgolek
 * Date: 2016-04-19
 * Time: 09:29
 */

namespace AppBundle\Entity\Import;


class BankData
{
    /** @var  string */
    private $bank_account;

    /** @var  string */
    private $commit_date;

    /**
     * @var string
     */
    private $transaction_date;

    /**
     * @var string
     */
    private $title;

    /**
     * @var  string
     */
    private $account;

    /**
     * @var  string
     */
    private $account_number;

    /**
     * @var  string
     */
    private $amount;

    /** @var string */
    private $journal_description_template = '{Title}, {Account}';

    /**
     * @return string
     */
    public function getCommitDate()
    {
        return $this->commit_date;
    }

    /**
     * @param string $commit_date
     */
    public function setCommitDate($commit_date)
    {
        $this->commit_date = $commit_date;
    }

    /**
     * @return string
     */
    public function getTransactionDate()
    {
        return $this->transaction_date;
    }

    /**
     * @param string $transaction_date
     */
    public function setTransactionDate($transaction_date)
    {
        $this->transaction_date = $transaction_date;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param string $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->account_number;
    }

    /**
     * @param string $account_number
     */
    public function setAccountNumber($account_number)
    {
        $this->account_number = $account_number;
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
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

    /**
     * @return string
     */
    public function getBankAccount()
    {
        return $this->bank_account;
    }

    /**
     * @param string $bank_account
     */
    public function setBankAccount($bank_account)
    {
        $this->bank_account = $bank_account;
    }
}