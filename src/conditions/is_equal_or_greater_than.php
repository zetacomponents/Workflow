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
 * Evaluates to true when the variable's value is greater than or
 * equal to the reference value.
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowConditionIsEqualOrGreaterThan extends ezcWorkflowConditionComparison
{
    /**
     * Evaluates this condition.
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
