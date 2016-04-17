<?php
/**
 * Created by PhpStorm.
 * User: bgolek
 * Date: 2016-04-15
 * Time: 11:10
 */

namespace AppBundle\Validator\Constraints\Journal;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VouchersSumIsZeroValidator extends ConstraintValidator
{
    public function validate($journal, Constraint $constraint)
    {
        $vouchers = [];
        foreach ($journal->getPositions() as $position)
        {
            $voucher            = $position->getVoucher();

            if (!key_exists($voucher, $vouchers))
                $vouchers[$voucher] = $position->getValue();
            else
                $vouchers[$voucher] = $vouchers[$voucher] + $position->getValue();
        }
        foreach ($vouchers as $voucher)
            if ($voucher != 0) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
    }
}