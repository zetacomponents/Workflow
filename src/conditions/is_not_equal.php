<?php
/**
 * File containing the ezcWorkflowConditionIsNotEqual class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Condition that checks if a value is different from a reference value.
 *
 * Typically used together with ezcWorkflowConditionVariable to use the
 * condition on a workflow variable.
 *
 * <code>
 * <?php
 * $condition = new ezcWorkflowConditionVariable(
 *   'variable name',
 *   new ezcWorkflowConditionNotEqual( $comparisonValue )
 * );
 * ?>
 * </code>
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowConditionIsNotEqual extends ezcWorkflowConditionComparison
{
    /**
     * @var mixed
     */
    protected $operator = '!=';

    /**
     * Evaluates this condition with $value and returns true if it is false or false if it is not.
     *
     * @param  mixed $value
     * @return boolean true when the condition holds, false otherwise.
     * @ignore
     */
    public function evaluate( $value )
    {
        return $value != $this->value;
    }
}
?>
