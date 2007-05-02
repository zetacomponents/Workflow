<?php
/**
 * File containing the ezcWorkflowConditionNot class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Boolean NOT.
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowConditionNot implements ezcWorkflowCondition
{
    /**
     * @var ezcWorkflowCondition
     */
    protected $condition;

    /**
     * Constructor.
     *
     * @param  ezcWorkflowCondition $condition
     */
    public function __construct( ezcWorkflowCondition $condition )
    {
        $this->condition = $condition;
    }

    /**
     * Evaluates this condition.
     *
     * @param  mixed $value
     * @return boolean true when the condition holds, false otherwise.
     */
    public function evaluate( $value )
    {
        return !$this->condition->evaluate( $value );
    }

    /**
     * Returns the condition that is negated.
     *
     * @return ezcWorkflowCondition
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Returns a textual representation of this condition.
     *
     * @return string
     */
    public function __toString()
    {
        return '! ' . (string) $this->condition;
    }
}
?>
