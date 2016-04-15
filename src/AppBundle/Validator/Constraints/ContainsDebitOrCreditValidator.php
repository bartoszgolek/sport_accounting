<?php
/**
 * Created by PhpStorm.
 * User: bgolek
 * Date: 2016-04-15
 * Time: 11:10
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsDebitOrCreditValidator extends ConstraintValidator
{
    public function validate($row, Constraint $constraint)
    {
        $debit = $row->getDebit();
        $credit = $row->getCredit();

        if ($debit == null && $credit == null) {
            $this->context->buildViolation($constraint->bothEmptyMessage)
                          ->atPath('debit')
                          ->addViolation();
        }

        if ($debit != null && $credit != null) {
            $this->context->buildViolation($constraint->bothFilledMessage)
                          ->atPath('debit')
                          ->addViolation();
        }
    }
}