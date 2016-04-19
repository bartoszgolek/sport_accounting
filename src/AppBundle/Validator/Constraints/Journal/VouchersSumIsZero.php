<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Gołek
 * Date: 2016-04-15
 * Time: 09:33
 */

namespace AppBundle\Validator\Constraints\Journal;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class VouchersSumIsZero extends Constraint
{
    public $message = "Sum of Every voucher should be 0. But for voucher %s is %d!";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}