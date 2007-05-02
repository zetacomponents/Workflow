<?php
/**
 * File containing the ezcWorkflowRollbackableServiceObject interface.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Interface for rollbackable service objects.
 *
 * @package Workflow
 * @version //autogen//
 */
interface ezcWorkflowRollbackableServiceObject extends ezcWorkflowServiceObject
{
    /**
     * Rolls back the action this service object performed before.
     *
     * @param ezcWorkflowExecution $execution
     */
    public function rollback( ezcWorkflowExecution $execution );
}
?>
