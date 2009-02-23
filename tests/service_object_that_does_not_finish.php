<?php
/**
 * File containing the ServiceObjectThatDoesNotFinish class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * A service object that does not finish.
 *
 * @package Workflow
 * @subpackage Tests
 * @version //autogen//
 */
class ServiceObjectThatDoesNotFinish implements ezcWorkflowServiceObject
{
    /**
     * Executes the business logic of this service object.
     *
     * @param ezcWorkflowExecution $execution
     * @return boolean $executionFinished
     */
    public function execute( ezcWorkflowExecution $execution )
    {
        return false;
    }

    /**
     * Returns a textual representation of this service object.
     *
     * @return string
     */
    public function __toString()
    {
        return '';
    }
}
?>
