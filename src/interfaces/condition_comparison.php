<?php
/**
 * File containing the ezcWorkflowConditionComparison class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Abstract base class for comparison conditions.
 *
 * @package Workflow
 * @version //autogen//
 */
abstract class ezcWorkflowConditionComparison implements ezcWorkflowCondition
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * Constructs a new comparison condition.
     *
     * Implemenations will compare $value to the value provided to evaluate().
     *
     * @param  mixed  $value
     */
    public function __construct( $value )
    {
        $this->value = $value;
    }

    /**
     * Returns the value that this condition compares against.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
?>
