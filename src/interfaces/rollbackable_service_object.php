<?php
/**
 * File containing the ezcWorkflowRollbackableServiceObject interface.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Interface for service objects for which the action can be rolled back
 * in case the workflow execution is cancelled.
 *
 * @package Workflow
 * @version //autogen//
 */
interface ezcWorkflowRollbackableServiceObject extends ezcWorkflowServiceObject
{
    /**
     * Rolls back the business logic of this service object.
     *
     * Implementors should return true when the rollback was successful
     * and false when it was not.
     *
     * @param  ezcWorkflowExecution $execution
     * @return boolean
     */
    public function rollback( ezcWorkflowExecution $execution );
}
?>
