<?php
/**
 * File containing the ezcWorkflowVisitorNodeCollector class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Collects all the nodes in a workflow in an array.
 *
 * @package Workflow
 * @version //autogen//
 * @ignore
 */
class ezcWorkflowVisitorNodeCollector implements ezcWorkflowVisitor
{
    /**
     * Holds the start node object.
     *
     * @var ezcWorkflowNodeStart
     */
    protected $startNode;

    /**
     * Holds the default end node object.
     *
     * @var ezcWorkflowNodeEnd
     */
    protected $endNode;

    /**
     * Holds the finally node object.
     *
     * @var ezcWorkflowNodeFinally
     */
    protected $finallyNode;

    /**
     * Flag that indicates whether the finally node has been visited.
     *
     * @var boolean
     */
    protected $finallyNodeVisited = false;

    /**
     * Holds the visited nodes.
     *
     * @var array(integer=>ezcWorkflowNode)
     */
    protected $nodes = array();

    /**
     * Holds the sequence of node ids.
     *
     * @var integer
     */
    protected $nextId = 0;

    /**
     * Flag that indicates whether the node list has been sorted.
     *
     * @var boolean
     */
    protected $sorted = false;

    /**
     * Holds the visited nodes.
     *
     * @var SplObjectStorage
     */
    protected $visited = array();

    /**
     * Constructs a new
     *
     * @param ezcWorkflow $workflow
     */
    public function __construct( ezcWorkflow $workflow )
    {
        $this->visited = new SplObjectStorage;
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
        if ( $visitable instanceof ezcWorkflow )
        {
            $visitable->startNode->setId( ++$this->nextId );
            $this->startNode = $visitable->startNode;

            $visitable->endNode->setId( ++$this->nextId );
            $this->endNode = $visitable->endNode;

            if ( count( $visitable->finallyNode->getOutNodes() ) > 0 )
            {
                $this->finallyNode = $visitable->finallyNode;
                $visitable->finallyNode->setId( ++$this->nextId );
            }
        }

        else if ( $visitable instanceof ezcWorkflowNode )
        {
            if ( $this->visited->contains( $visitable ) )
            {
                return false;
            }

            $this->visited->attach( $visitable );

            if ( $visitable !== $this->startNode &&
                 $visitable !== $this->endNode &&
                 $visitable !== $this->finallyNode )
            {
                $id = ++$this->nextId;
                $visitable->setId( $id );
            }
            else
            {
                $id = $visitable->getId();
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
        if ( $this->finallyNode !== null && !$this->finallyNodeVisited )
        {
            $this->finallyNode->accept( $this );
            $this->finallyNode = true;
        }

        if ( !$this->sorted )
        {
            ksort( $this->nodes );
            $this->sorted = true;
        }

        return $this->nodes;
    }
}
?>
