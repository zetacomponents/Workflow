<?php
/**
 * File containing the ezcWorkflowNodeEnd class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * An object of the ezcWorkflowNodeEnd class represents an end node of a workflow.
 *
 * A workflow must have at least one end node. The execution of the workflow ends
 * when an end node is reached.
 * Creating an object of the ezcWorkflow class automatically creates a default end node for the new
 * workflow. It can be accessed through the getEndNode() method.
 *
 * Incomming nodes: 1
 * Outgoing nodes: 0
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeEnd extends ezcWorkflowNode
{
    /**
     * Constraint: The minimum number of outgoing nodes this node has to have
     * to be valid.
     *
     * @var integer
     */
    protected $minOutNodes = 0;

    /**
     * Constraint: The maximum number of outgoing nodes this node has to have
     * to be valid.
     *
     * @var integer
     */
    protected $maxOutNodes = 0;

    /**
     * Executes this node.
     *
     * @param ezcWorkflowExecution $execution
     * @ignore
     */
    public function execute( ezcWorkflowExecution $execution )
    {
        $execution->end( $this );

        return parent::execute( $execution );
    }
}
?>
