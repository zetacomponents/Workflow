<?php
/**
 * File containing the ezcWorkflowExecutionNonInteractive class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Workflow execution engine for non-interactive workflows.
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowExecutionNonInteractive extends ezcWorkflowExecution
{
    /**
     * Sets the workflow.
     *
     * @param  ezcWorkflow $workflow
     * @throws ezcWorkflowExecutionException
     */
    public function setWorkflow( ezcWorkflow $workflow )
    {
        if ( $workflow->isInteractive() || $workflow->hasSubWorkflows() )
        {
            throw new ezcWorkflowExecutionException(
              'This executer can only execute workflow that have no Input and SubWorkflow nodes.'
            );
        }

        parent::setWorkflow( $workflow );
    }

    /**
     * Start workflow execution.
     *
     * @param  integer $parentId
     */
    protected function doStart( $parentId )
    {
    }

    /**
     * Suspend workflow execution.
     */
    protected function doSuspend()
    {
    }

    /**
     * Resume workflow execution.
     *
     * @param integer $executionId  ID of the execution to resume.
     */
    protected function doResume( $executionId )
    {
    }

    /**
     * End workflow execution.
     */
    protected function doEnd()
    {
    }

    /**
     * Returns a new execution object for a sub workflow.
     *
     * @param  integer $id
     * @return ezcWorkflowExecution
     */
    protected function doGetSubExecution( $id = NULL )
    {
    }
}
?>
