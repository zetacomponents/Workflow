<?php
/**
 * File containing the ezcWorkflowConditionOr class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Boolean OR.
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowConditionOr extends ezcWorkflowConditionBooleanSet
{
    /**
     * @var string
     */
    protected $concatenation = '||';

    /**
     * Evaluates this condition.
     *
     * @param  mixed $value
     * @return boolean true when the condition holds, false otherwise.
     */
    public function evaluate( $value )
    {
        foreach ( $this->conditions as $condition )
        {
            if ( $condition->evaluate( $value ) )
            {
                return true;
            }
        }

        return false;
    }
}
?>
