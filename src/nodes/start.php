<?php
/**
 * File containing the ezcWorkflowNodeStart class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * This node marks the start of the workflow.
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeStart extends ezcWorkflowNode
{
    /**
     * Constraint: The minimum number of incoming nodes this node has to have
     * to be valid.
     *
     * @var integer
     */
    protected $minInNodes = 0;

    /**
     * Constraint: The maximum number of incoming nodes this node has to have
     * to be valid.
     *
     * @var integer
     */
    protected $maxInNodes = 0;

    /**
     * Executes this node.
     *
     * @param ezcWorkflowExecution $execution
     */
    public function execute( ezcWorkflowExecution $execution )
    {
        $this->outNodes[0]->activate(
          $execution,
          $this,
          $execution->startThread()
        );

        return parent::execute( $execution );
    }
}
?>
