<?php
/**
 * File containing the ezcWorkflowConditionIsBool class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Condition that evaluates to true if the evaluated value is a boolean.
 *
 * Typically used together with ezcWorkflowConditionVariable to use the
 * condition on a workflow variable.
 *
 * <code>
 * <?php
 * $condition = new ezcWorkflowConditionVariable(
 *   'variable name',
 *   new ezcWorkflowConditionIsBoolean
 * );
 * ?>
 * </code>
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowConditionIsBool extends ezcWorkflowConditionType
{
    /**
     * Evaluates this condition and returns true if $value is a boolean or false if not.
     *
     * @param  mixed $value
     * @return boolean true when the condition holds, false otherwise.
     * @ignore
     */
    public function evaluate( $value )
    {
        return is_bool( $value );
    }

    /**
     * Returns a textual representation of this condition.
     *
     * @return string
     * @ignore
     */
    public function __toString()
    {
        return 'is bool';
    }
}
?>
