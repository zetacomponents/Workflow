<?php
/**
 * File containing the ezcWorkflowVisitorVisualization class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * An implementation of the ezcWorkflowVisitor interface that
 * generates GraphViz/dot markup for a workflow definition.
 *
 * <code>
 *  $visitor = new ezcWorkflowVisitorVisualization;
 *  $workflow->accept( $visitor );
 *  echo (string)$visitor; // print the plot
 * </code>
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowVisitorVisualization implements ezcWorkflowVisitor
{
    /**
     * Holds the displayed strings for each of the nodes.
     *
     * @var array(string => string)
     */
    protected $nodes = array();

    /**
     * Holds all the edges of the graph.
     *
     * @var array( id => array( ezcWorkflowNode ) )
     */
    protected $edges = array();

    /**
     * Holds the id of each node that has been visited already.
     *
     * @var array
     */
    protected $visited = array();

    /**
     * The name of the workflow.
     *
     * @var string
     */
    protected $workflowName = 'Workflow';

    /**
     * Visits the node and sets the the member variables according to the node
     * type and contents.
     *
     * @param ezcWorkflowVisitable $node
     * @return boolean
     */
    public function visit( ezcWorkflowVisitable $visitable )
    {
        if ( $visitable instanceof ezcWorkflow )
        {
            $this->workflowName = $visitable->name;

            foreach ( $visitable->nodes as $id => $node )
            {
                $node->setId( $id + 1 );
            }
        }

        if ( $visitable instanceof ezcWorkflowNode )
        {
            $id = $visitable->getId();

            if ( isset( $this->visited[$id] ) )
            {
                return false;
            }

            $this->visited[$id] = true;

            if ( !isset( $this->nodes[ $id ] ) )
            {
                $this->nodes[ $id ] = (string)$visitable;
            }

            $outNodes = array();

            foreach ( $visitable->getOutNodes() as $outNode )
            {
                $label = '';

                if ( $visitable instanceof ezcWorkflowNodeConditionalBranch )
                {
                    $condition = $visitable->getCondition( $outNode );

                    if ( $condition !== false )
                    {
                        $label = ' [label="' . (string) $condition . '"]';
                    }
                }

                $outNodes[] = array( $outNode->getId(), $label );
            }

            $this->edges[ $id ] = $outNodes;
        }

        return true;
    }

    /**
     * Returns a the contents of a graphviz .dot file.
     *
     * @return boolean
     */
    public function __toString()
    {
        $dot = 'digraph ' . $this->workflowName . " {\n";

        foreach ( $this->nodes as $key => $value )
        {
            $dot .= sprintf(
              "node%s [label=\"%s\"]\n",
              $key,
              $value
            );
        }

        $dot .= "\n";

        foreach ( $this->edges as $fromNode => $toNodes )
        {
            foreach ( $toNodes as $toNode )
            {
                $dot .= sprintf(
                  "node%s -> node%s%s\n",

                  $fromNode,
                  $toNode[0],
                  $toNode[1]
                );
            }
        }

        return $dot . "}\n";
    }
}
?>
