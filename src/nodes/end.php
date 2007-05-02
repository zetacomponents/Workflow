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
 * This node marks an end of the workflow.
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
     */
    public function execute( ezcWorkflowExecution $execution )
    {
        $execution->end( $this );

        return parent::execute( $execution );
    }
}
?>
