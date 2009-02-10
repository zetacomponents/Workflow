<?php
/**
 * File containing the ezcWorkflowVisitorMaxNodeIdFinder class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Visitor that finds the maximum node id in a workflow.
 *
 * @package Workflow
 * @version //autogen//
 * @ignore
 */
class ezcWorkflowVisitorMaxNodeIdFinder implements ezcWorkflowVisitor
{
    /**
     * Holds the visited nodes.
     *
     * @var array(ezcWorkflowVisitable)
     */
    protected $nodes = array();

    /**
     * Holds the current maximum node id.
     *
     * @var integer
     */
    protected $maxNodeId = 0;

    /**
     * Constructor.
     *
     * @param ezcWorkflow $workflow
     */
    public function __construct( ezcWorkflow $workflow )
    {
        $workflow->accept( $this );
    }

    /**
     * Visits the node, adds it to the list of nodes.
     *
     * Returns true if the node was added. False if it was already in the list
     * of nodes.
     *
     * @param ezcWorkflowVisitable $visitable
     * @return boolean
     */
    public function visit( ezcWorkflowVisitable $visitable )
    {
        if ( $visitable instanceof ezcWorkflowNode )
        {
            $id = $visitable->getId();

            if ( $id !== false )
            {
                $this->maxNodeId = max( $this->maxNodeId, $id );
            }

            if ( isset( $this->nodes[$id] ) )
            {
                return false;
            }

            $this->nodes[$id] = $visitable;
        }

        return true;
    }

    /**
     * Returns the maximum node id.
     *
     * @return array
     */
    public function getMaxNodeId()
    {
        return $this->maxNodeId;
    }
}
?>
