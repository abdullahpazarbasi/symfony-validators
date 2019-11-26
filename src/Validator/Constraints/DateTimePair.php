<?php

namespace SfV\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * Class DateTimePair
 *
 * @Annotation
 */
class DateTimePair extends Constraint
{

    /**
     * @var string
     */
    public $invalidTypeMessage = "{{ key }} has invalid type.";

    /**
     * @var string
     */
    public $invalidOpposingComponentTypeMessage = "{{ opposing_component_key }} has invalid type.";

    /**
     * @var string
     */
    public $canNotBeEarlierMessage = "{{ key }} can not be earlier than {{ opposing_component_key }}.";

    /**
     * @var string
     */
    public $canNotBeLaterMessage = "{{ key }} can not be later than {{ opposing_component_key }}.";

    /**
     * @var string
     */
    public $canNotBeEqualMessage = "{{ key }} and {{ opposing_component_key }} can not be equal.";

    /**
     * @var bool
     */
    public $earlier;

    /**
     * @var bool
     */
    public $later;

    /**
     * @var string
     */
    public $opposingComponentKey;

    /**
     * @var bool
     */
    public $nullableOpposingComponent = false;

    /**
     * @var bool
     */
    public $canBeEqual = false;

    /**
     * @param array|null $options
     */
    public function __construct($options = null)
    {
        parent::__construct($options);

        if (null === $this->earlier && null === $this->later) {
            throw new MissingOptionsException(sprintf('Either option "earlier" or "later" must be given for constraint %s', __CLASS__), ['earlier', 'later']);
        }

        if (true === $this->earlier && true === $this->later) {
            throw new ConstraintDefinitionException(sprintf('Both option "earlier" and "later" can not be true for constraint %s', __CLASS__));
        }
    }

    /**
     * @inheritDoc
     */
    public function getRequiredOptions()
    {
        return ['opposingComponentKey'];
    }

}