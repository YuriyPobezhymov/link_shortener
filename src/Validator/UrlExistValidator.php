<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UrlExistValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
      /* @var $constraint \App\Validator\UrlExist */
      $headers = @get_headers($value);
      if ($headers && strpos( $headers[0], '200')) {
          return;
      }

      $this->context->buildViolation($constraint->message)
          ->setParameter('{{ value }}', $value)
          ->addViolation();
    }
}
