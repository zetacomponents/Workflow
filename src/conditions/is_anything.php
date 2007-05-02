<?php
/**
 * File containing the ezcWorkflowConditionIsAnything class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Evaluates always to true.
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowConditionIsAnything extends ezcWorkflowConditionType
{
    /**
     * Evaluates this condition.
     *
     * @param  mixed $value
     * @return boolean true
     */
    public function evaluate( $value )
    {
        return true;
    }

    /**
     * Returns a textual representation of this condition.
     *
     * @return string
     */
    public function __toString()
    {
        return 'is anything';
    }
}
?>
