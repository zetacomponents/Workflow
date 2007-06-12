<?php
/**
 * File containing the ezcWorkflowNodeSimpleMerge class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * This node implements the Simple Merge (XOR-Join) workflow pattern.
 *
 * The Simple Merge workflow pattern is to be used to merge the possible paths that are defined
 * by a preceding Exclusive Choice. It is assumed that of these possible paths exactly one is
 * taken and no synchronization takes place.
 *
 * Use Case Example: After the payment has been performed by either credit card or bank
 * transfer, the order can be processed further.
 *
 * Incoming nodes: 2..*
 * Outgoing nodes: 1
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeSimpleMerge extends ezcWorkflowNodeMerge
{
    /**
     * Activate this node.
     *
     * @param ezcWorkflowExecution $execution
     * @param ezcWorkflowNode $activatedFrom
     * @param int $threadId
     * @ignore
     */
    public function activate( ezcWorkflowExecution $execution, ezcWorkflowNode $activatedFrom = null, $threadId = 0 )
    {
        $parentThreadId = $execution->getParentThreadId( $threadId );

        if ( empty( $this->state ) )
        {
            $this->state[] = $threadId;

            parent::activate( $execution, $activatedFrom, $parentThreadId );
        }
    }

    /**
     * Executes this node.
     *
     * @param ezcWorkflowExecution $execution
     * @ignore
     */
    public function execute( ezcWorkflowExecution $execution )
    {
        return $this->doMerge( $execution );
    }
}
?>
