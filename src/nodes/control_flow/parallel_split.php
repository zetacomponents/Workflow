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
 * The Parallel Split work?ow pattern divides one thread of execution
 * unconditionally into multiple parallel threads of execution.
 *
 * Use Case Example: After the credit card specified by the customer has been successfully
 * charged, the activities of sending a confirmation email and starting the shipping process can
 * be executed in parallel.
 *
 * Incoming nodes: 1
 * Outgoing nodes: 2..*
 *
 * $todo example
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeParallelSplit extends ezcWorkflowNodeBranch
{
    /**
     * Activates all outgoing nodes.
     *
     * @param ezcWorkflowExecution $execution
     * @ignore
     */
    public function execute( ezcWorkflowExecution $execution )
    {
        return $this->activateOutgoingNodes( $execution, $this->outNodes );
    }
}
?>
