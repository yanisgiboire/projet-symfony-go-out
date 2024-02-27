<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LimitDateInscription extends Constraint
{
    public $message = 'La date limite d\'inscription ne peut pas être antérieure à la date de début de l\'événement.';
}