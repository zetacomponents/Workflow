<?php
/**
 * File containing the ezcWorkflowNodeBranch class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Base class for nodes that branch multiple threads of execution.
 *
 * @package Workflow
 * @version //autogen//
 */
abstract class ezcWorkflowNodeBranch extends ezcWorkflowNode
{
    /**
     * Constraint: The minimum number of outgoing nodes this node has to have
     * to be valid.
     *
     * @var integer
     */
    protected $minOutNodes = 2;

    /**
     * Constraint: The maximum number of outgoing nodes this node has to have
     * to be valid.
     *
     * @var integer
     */
    protected $maxOutNodes = false;

    /**
     * Activates this node's outgoing nodes.
     *
     * @param ezcWorkflowExecution $execution
     * @param array $nodes
     */
    protected function activateOutgoingNodes( ezcWorkflowExecution $execution, Array $nodes )
    {
        $threadId           = $this->getThreadId();
        $numNodesToActivate = count( $nodes );

        foreach ( $nodes as $node )
        {
            $node->activate(
              $execution,
              $this,
              $execution->startThread( $threadId, $numNodesToActivate )
            );
        }

        return parent::execute( $execution );
    }
}
?>
