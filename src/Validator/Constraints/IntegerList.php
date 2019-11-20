<?php

namespace SfV\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class IntegerList
 *
 * @Annotation
 */
class IntegerList extends Constraint
{

    /**
     * @var string
     */
    public $invalidTypeMessage = "It has invalid type. It should be an array of integers.";

    /**
     * @var string
     */
    public $invalidIndexMessage = "It should be a sequential array. The key of the element number {{ index }} is {{ key }}.";

    /**
     * @var string
     */
    public $invalidElementTypeMessage = "The type of the element number {{ index }} should be integer. {{ value }} was given.";

    /**
     * @var string
     */
    public $lowerLimitMessage = "The value of the element number {{ index }} should be greater than or equal to {{ limit }}. {{ value }} was given.";

    /**
     * @var string
     */
    public $upperLimitMessage = "The value of the element number {{ index }} should be less than or equal to {{ limit }}. {{ value }} was given.";

    /**
     * @var int
     */
    public $min;

    /**
     * @var int
     */
    public $max;

}