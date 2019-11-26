<?php

namespace SfV\Validator\Constraints;

use DateTime;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class DateTimePairValidator
 */
class DateTimePairValidator extends ConstraintValidator
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
        if (!($constraint instanceof DateTimePair)) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\DateTimePair');
        }

        if (null === $value) {
            return;
        }

        if (!($value instanceof DateTime)) {
            $this->context->buildViolation($constraint->invalidTypeMessage)
                ->addViolation();
            return;
        }

        $opposingComponentKey = $constraint->opposingComponentKey;
        $earlier = $constraint->earlier;
        $later = $constraint->later;
        $nullableOpposingComponent = $constraint->nullableOpposingComponent;
        $canBeEqual = $constraint->canBeEqual;

        $key = trim($this->context->getPropertyPath(), "[]");
        $values = $this->context->getRoot();
        $opposingComponentValue = $values[$opposingComponentKey];

        if ($nullableOpposingComponent && null === $opposingComponentValue) {
            return;
        }

        if (!($opposingComponentValue instanceof DateTime)) {
            $this->context->buildViolation($constraint->invalidOpposingComponentTypeMessage)
                ->addViolation();
            return;
        }

        $value1 = $value->getTimestamp();
        $value2 = $opposingComponentValue->getTimestamp();
        if ($value1 == $value2) { // if at the same time
            if (!$canBeEqual) {
                $this->context->buildViolation($constraint->canNotBeEqualMessage)
                    ->setParameter('{{ key }}', $this->formatValue($key))
                    ->setParameter('{{ opposing_component_key }}', $this->formatValue($opposingComponentKey))
                    ->addViolation();
            }
        } elseif ($value1 > $value2) { // if later
            if ($earlier) {
                $this->context->buildViolation($constraint->canNotBeLaterMessage)
                    ->setParameter('{{ key }}', $this->formatValue($key))
                    ->setParameter('{{ opposing_component_key }}', $this->formatValue($opposingComponentKey))
                    ->addViolation();
            }
        } else { // if earlier
            if ($later) {
                $this->context->buildViolation($constraint->canNotBeEarlierMessage)
                    ->setParameter('{{ key }}', $this->formatValue($key))
                    ->setParameter('{{ opposing_component_key }}', $this->formatValue($opposingComponentKey))
                    ->addViolation();
            }
        }
    }

}
