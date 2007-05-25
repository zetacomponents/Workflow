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
 * </code>
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowVisitorVisualization implements ezcWorkflowVisitor
{
    /**
     * @var array
     */
    protected $nodes = array();

    /**
     * @var array
     */
    protected $edges = array();

    /**
     * @var array
     */
    protected $visited = array();

    /**
     * @var string
     */
    protected $workflowName = 'Workflow';

    /**
     * @param ezcWorkflowVisitable $node
     * @return boolean
     */
    public function visit( ezcWorkflowVisitable $visitable )
    {
        foreach ( $this->visited as $visited )
        {
            if ( $visited === $visitable )
            {
                return false;
            }
        }

        $this->visited[] = $visitable;

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
