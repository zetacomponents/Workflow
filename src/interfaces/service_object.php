<?php
/**
 * File containing the ezcWorkflowServiceObject interface.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Interface for service objects that can be attached to
 * ezcWorkflowNodeAction nodes.
 *
 * @package Workflow
 * @version //autogen//
 */
interface ezcWorkflowServiceObject
{
    /**
     * Executes the business logic of this service object.
     *
     * @param ezcWorkflowExecution $execution
     */
    public function execute( ezcWorkflowExecution $execution );

    /**
     * Returns a textual representation of this service object.
     *
     * @return string
     */
    public function __toString();
}
?>
