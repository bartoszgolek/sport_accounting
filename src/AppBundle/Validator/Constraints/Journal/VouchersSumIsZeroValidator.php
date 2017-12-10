<?php
/**
 * User: Bartosz Gołek
 * Date: 2016-04-15
 * Time: 11:10
 */

namespace AppBundle\Validator\Constraints\Journal;


use AppBundle\Entity\Documents\Journal;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VouchersSumIsZeroValidator extends ConstraintValidator
{
    public function validate($journal, Constraint $constraint)
    {
        /**
         * @var Journal           $journal
         * @var VouchersSumIsZero $constraint
         */
        $vouchers = [];
        foreach ($journal->getPositions() as $position)
        {
            $voucher            = $position->getVoucher();

            if (!key_exists($voucher, $vouchers)) {
                $vouchers[$voucher] = $position->getValue();
            } else {
                $vouchers[$voucher] = $vouchers[$voucher] + $position->getValue();
            }
        }
        foreach ($vouchers as $voucher => $value) {
            $value = round($value, 2);
            if ($value != 0) {
                $this->context->buildViolation(sprintf($constraint->message, $voucher, $value))
                              ->addViolation();
            }
        }
    }
}