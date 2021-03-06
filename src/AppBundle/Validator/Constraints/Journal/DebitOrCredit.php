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
class DebitOrCredit extends Constraint
{
    public $bothFilledMessage = 'Only Debit or Credit can be filled.';
    public $bothEmptyMessage = 'Debit or Credit has to be filled.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}