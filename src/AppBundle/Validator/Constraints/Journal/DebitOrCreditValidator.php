<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Gołek
 * Date: 2016-04-15
 * Time: 11:10
 */

namespace AppBundle\Validator\Constraints\Journal;


use AppBundle\Entity\Documents\Journal;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DebitOrCreditValidator extends ConstraintValidator
{
    public function validate($journal, Constraint $constraint)
    {
        /**
         * @var Journal $journal
         * @var DebitOrCredit $constraint
         */
        foreach ($journal->getPositions() as $index => $position)
        {
            $debit = $position->getDebit();
            $credit = $position->getCredit();

            if ($debit == null && $credit == null) {
                $this->context->buildViolation($constraint->bothEmptyMessage)
                              ->atPath('positions')->atPath($index)->atPath('debit')
                              ->addViolation();
            }

            if ($debit != null && $credit != null) {
                $this->context->buildViolation($constraint->bothFilledMessage)
                              ->atPath('positions')->atPath($index)->atPath('debit')
                              ->addViolation();
            }
        }
    }
}