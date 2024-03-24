<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class TaxCode extends Constraint
{
    public string $message = 'Tax code {{ string }} failed';

    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }
}
