<?php

namespace SfV\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class IntegerListValidator
 */
class IntegerListValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!($constraint instanceof IntegerList)) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\IntegerList');
        }
        if (null === $value) {
            return;
        }
        if (!is_array($value)) {
            $this->context->buildViolation($constraint->invalidTypeMessage)
                ->addViolation();
            return;
        }
        $min = $constraint->min;
        $max = $constraint->max;
        $expectedElementKey = 0;
        foreach ($value as $elementKey => $elementValue) {
            if ($elementKey !== $expectedElementKey++) {
                $this->context->buildViolation($constraint->invalidIndexMessage)
                    ->setParameter('{{ index }}', $this->formatValue($expectedElementKey))
                    ->setParameter('{{ key }}', $this->formatValue($elementKey))
                    ->addViolation();
                return;
            }
            if (!is_int($elementValue)) {
                $this->context->buildViolation($constraint->invalidElementTypeMessage)
                    ->setParameter('{{ index }}', $this->formatValue($expectedElementKey))
                    ->setParameter('{{ value }}', $this->formatValue($elementValue))
                    ->addViolation();
                return;
            }
            if (null !== $max && $elementValue > $max) {
                $this->context->buildViolation($constraint->upperLimitMessage)
                    ->setParameter('{{ index }}', $this->formatValue($expectedElementKey))
                    ->setParameter('{{ value }}', $this->formatValue($elementValue))
                    ->setParameter('{{ limit }}', $this->formatValue($max))
                    ->addViolation();
                return;
            }
            if (null !== $min && $elementValue < $min) {
                $this->context->buildViolation($constraint->lowerLimitMessage)
                    ->setParameter('{{ index }}', $this->formatValue($expectedElementKey))
                    ->setParameter('{{ value }}', $this->formatValue($elementValue))
                    ->setParameter('{{ limit }}', $this->formatValue($min))
                    ->addViolation();
                return;
            }
        }
    }

}