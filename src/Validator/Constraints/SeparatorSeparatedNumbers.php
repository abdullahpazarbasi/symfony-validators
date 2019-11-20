<?php

namespace SfV\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class SeparatorSeparatedNumbers
 *
 * @Annotation
 */
class SeparatorSeparatedNumbers extends Constraint
{

    /**
     * @var string
     */
    public $messageInvalidList = "The value '{{ string }}' is not a valid number list";

    /**
     * @var string
     */
    public $messageInvalidSeparator = "'{{ string }}' is not a valid separator";

    /**
     * @var string
     */
    public $messageElementLowerLimit = "The value of the element number {{ index }} should be greater than or equal to {{ limit }}. {{ value }} was given.";

    /**
     * @var string
     */
    public $messageElementUpperLimit = "The value of the element number {{ index }} should be less than or equal to {{ limit }}. {{ value }} was given.";

    /**
     * @var string
     */
    public $separator = ',';

    /**
     * @var bool
     */
    public $onlyIntegers = true;

    /**
     * @var float
     */
    public $lowerLimitOfAnElement;

    /**
     * @var float
     */
    public $upperLimitOfAnElement;

}