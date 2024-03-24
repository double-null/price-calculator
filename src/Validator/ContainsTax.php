<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class ContainsTax extends Constraint
{
    public $message = "Не верный код";
}
