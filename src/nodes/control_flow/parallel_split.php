<?php
/**
 * File containing the ezcWorkflowNodeParallelSplit class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * This node implements the Parallel Split workflow pattern.
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeParallelSplit extends ezcWorkflowNodeBranch
{
    /**
     * Executes this node.
     *
     * @param ezcWorkflowExecution $execution
     */
    public function execute( ezcWorkflowExecution $execution )
    {
        return $this->activateOutgoingNodes( $execution, $this->outNodes );
    }
}
?>
