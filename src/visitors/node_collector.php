<?php
/**
 * File containing the ezcWorkflowVisitorNodeCollector class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Collects all the nodes in an array.
 *
 * @package Workflow
 * @version //autogen//
 * @access private
 */
class ezcWorkflowVisitorNodeCollector implements ezcWorkflowVisitor
{
    /**
     * Holds the visited nodes.
     *
     * @var array(ezcWorkflowVisitable)
     */
    protected $nodes = array();

    /**
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
        foreach ( $this->nodes as $node )
        {
            if ( $node === $visitable )
            {
                return false;
            }
        }

        if ( $visitable instanceof ezcWorkflowNode )
        {
            $this->nodes[] = $visitable;
        }

        return true;
    }

    /**
     * Returns the collected nodes.
     *
     * @return array
     */
    public function getNodes()
    {
        return $this->nodes;
    }
}
?>
