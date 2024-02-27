<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Entity\GoOut;

class LimitDateInscriptionValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        // Récupérer les données soumises au validateur
        $formData = $this->context->getObject();

        // Assurez-vous que formData est bien un objet GoOut
        if (!$formData instanceof GoOut) {
            return;
        }

        // Récupérer la valeur de startDateTime à partir des données du formulaire
        $startDateTime = $formData->getStartDateTime();

        if ($value < $startDateTime) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
