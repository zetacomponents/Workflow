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
     * Holds the sequence of node ids.
     *
     * @var integer
     */
    protected $nextId = 1;

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
        if ( $visitable instanceof ezcWorkflowNode )
        {
            $id = $visitable->getId();

            if ( $id === false )
            {
                $id = $this->nextId++;
                $visitable->setId( $id );
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
     * Returns the collected nodes.
     *
     * @return array
     */
    public function getNodes()
    {
        return array_values( $this->nodes );
    }
}
?>
