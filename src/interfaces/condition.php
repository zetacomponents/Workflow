<?php
/**
 * File containing the ezcWorkflowCondition interface.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Interface for workflow conditions.
 *
 * @package Workflow
 * @version //autogen//
 */
interface ezcWorkflowCondition
{
    /**
     * Evaluates this condition.
     *
     * @param  mixed $value
     * @return boolean true when the condition holds, false otherwise.
     */
    public function evaluate( $value );

    /**
     * Returns a textual representation of this condition.
     *
     * @return string
     */
    public function __toString();
}
?>
