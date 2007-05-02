<?php
/**
 * File containing the ezcWorkflowConditionIsBool class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Evaluates to true when the variable is boolean.
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowConditionIsBool extends ezcWorkflowConditionType
{
    /**
     * Evaluates this condition.
     *
     * @param  mixed $value
     * @return boolean true when the condition holds, false otherwise.
     */
    public function evaluate( $value )
    {
        return is_bool( $value );
    }

    /**
     * Returns a textual representation of this condition.
     *
     * @return string
     */
    public function __toString()
    {
        return 'is bool';
    }
}
?>
