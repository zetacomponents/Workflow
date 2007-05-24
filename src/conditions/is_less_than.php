<?php
/**
 * File containing the ezcWorkflowConditionIsLessThan class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Condition that evaluates to true if the provided value is less than the reference value.
 *
 * Typically used together with ezcWorkflowConditionVariable to use the
 * condition on a workflow variable.
 *
 * <code>
 *  $condition = new ezcWorkflowConditionVariable ( 'variable name' ,
 *     new ezcWorkflowConditionIsLessThan ( $comparisonValue )
 *  );
 * </code>
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowConditionIsLessThan extends ezcWorkflowConditionComparison
{
    /**
     * Evaluates this condition with $value and returns true if $value is less than
     * the reference value or false if not.
     *
     * @param  mixed $value
     * @return boolean true when the condition holds, false otherwise.
     */
    public function evaluate( $value )
    {
        return $value < $this->value;
    }

    /**
     * Returns a textual representation of this condition.
     *
     * @return string
     */
    public function __toString()
    {
        return '< ' . $this->value;
    }
}
?>
