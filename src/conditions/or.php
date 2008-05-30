<?php
/**
 * File containing the ezcWorkflowConditionOr class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Boolean OR.
 *
 * An object of the ezcWorkflowConditionOr class represents a boolean OR expression. It can
 * hold an arbitrary number of ezcWorkflowCondition objects.
 *
 * <code>
 * <?php
 * $or = new ezcWorkflowConditionOr( array ( $condition , ... ) );
 * ?>
 * </code>
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
     * Evaluates this condition with $value and returns true if the condition holds and false otherwise.
     *
     * @param  mixed $value
     * @return boolean true when the condition holds, false otherwise.
     * @ignore
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
