<?php

namespace SfV\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class SeparatorSeparatedNumbersValidator
 */
class SeparatorSeparatedNumbersValidator extends ConstraintValidator
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
        if (!($constraint instanceof SeparatorSeparatedNumbers)) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\SeparatorSeparatedNumbers');
        }
        if (null === $value) {
            return;
        }
        $separator = $constraint->separator;
        if (!in_array($separator, [',', ';', '|', ':', "\\"], true)) {
            $this->context->buildViolation($constraint->messageInvalidSeparator)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
            return;
        }
        $onlyIntegers = $constraint->onlyIntegers;
        $escapedSeparator = "\\" . $separator;
        if ($onlyIntegers) {
            $pattern = '^(?:0|-?[1-9]\d*)(?:' . $escapedSeparator . '(?:0|-?[1-9]\d*))*$';
        } else {
            $pattern = '^(?:0|-?[1-9]\d*)(?:\.\d+)?(?:' . $escapedSeparator . '(?:0|-?[1-9]\d*)(?:\.\d+)?)*$';
        }
        if (!preg_match('/' . $pattern . '/', $value)) {
            $this->context->buildViolation($constraint->messageInvalidList)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
            return;
        }
        $min = $constraint->lowerLimitOfAnElement;
        $max = $constraint->upperLimitOfAnElement;
        if (null === $min && null === $max) {
            return;
        }
        $elements = explode($separator, $value);
        foreach ($elements as $index => $element) {
            $elementValue = $onlyIntegers ? (int)$element : (float)$element;
            if (null !== $max && $elementValue > $max) {
                $this->context->buildViolation($constraint->messageElementUpperLimit)
                    ->setParameter('{{ index }}', $this->formatValue($index))
                    ->setParameter('{{ value }}', $this->formatValue($elementValue))
                    ->setParameter('{{ limit }}', $this->formatValue($max))
                    ->addViolation();
                return;
            }
            if (null !== $min && $elementValue < $min) {
                $this->context->buildViolation($constraint->messageElementLowerLimit)
                    ->setParameter('{{ index }}', $this->formatValue($index))
                    ->setParameter('{{ value }}', $this->formatValue($elementValue))
                    ->setParameter('{{ limit }}', $this->formatValue($min))
                    ->addViolation();
                return;
            }
        }
    }

}