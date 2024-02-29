<?php

namespace App\Validator\Constraints;

use App\Entity\GoOut;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class LimitDateInscriptionValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint): void
    {
        /* @var $constraint LimitDateInscription */
        $form = $this->context->getRoot();
        $data = $form->getData();

        if (!$data instanceof GoOut) {
            return;
        }

        $startDate = $data->getStartDateTime();
        if ($value >= $startDate) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}