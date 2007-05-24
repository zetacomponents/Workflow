<?php
/**
 * File containing the ezcWorkflowConditionIsEqualOrGreaterThan class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Condition that evaluates to true if the provided value is greater than or equal to the reference value.
 *
 * Typically used together with ezcWorkflowConditionVariable to use the
 * condition on a workflow variable.
 *
 * <code>
 *  $condition = new ezcWorkflowConditionVariable ( 'variable name' ,
 *     new ezcWorkflowConditionIsEqualOrGreatherThan ( $comparisonValue )
 *  );
 * </code>
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowConditionIsEqualOrGreaterThan extends ezcWorkflowConditionComparison
{
    /**
     * Evaluates this condition with $value and returns true if $value is greather than
     * or equal to the reference value or false if not.
     *
     * @param  mixed $value
     * @return boolean true when the condition holds, false otherwise.
     */
    public function evaluate( $value )
    {
        return $value >= $this->value;
    }

    /**
     * Returns a textual representation of this condition.
     *
     * @return string
     */
    public function __toString()
    {
        return '>= ' . $this->value;
    }
}
?>
