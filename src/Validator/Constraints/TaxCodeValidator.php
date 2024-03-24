<?php

namespace App\Validator\Constraints;

use App\Entity\Country;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TaxCodeValidator extends ConstraintValidator
{
    private $countryRepo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->countryRepo = $em->getRepository(Country::class);
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        $error = false;
        $countryTag = substr($value, 0, 2);
        $country = $this->countryRepo->findOneByTag($countryTag);
        if (!empty($country)) {
            // Преобразуем маску в regexp
            $mask = '~^'.str_replace(
                    ['X', 'Y'],
                    ['[0-9]', '[A-Z]'],
                    $country->getTaxFormat(),
                ).'$~';
            if (!preg_match($mask, $value)) {
                $error = true;
            }
        } else {
            $error = true;
        }

        if ($error) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', (string) $value)
                ->addViolation();
        }
    }
}