<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class LimitDateInscription extends Constraint
{
    public $message = 'La date limite d\'inscription doit être antérieure à la date de début de la sortie.';
}