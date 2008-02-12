<?php
/**
 * File containing the ezcWorkflowVisitorVisualization class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * An implementation of the ezcWorkflowVisitor interface that
 * generates GraphViz/dot markup for a workflow definition.
 *
 * <code>
 *  $visitor = new ezcWorkflowVisitorVisualization;
 *  $workflow->accept( $visitor );
 *  print $visitor;
 * </code>
 *
 * @property string $colorHighlighted Color used for highlighted nodes.
 * @property string $colorNormal      Color used for non-highlighted nodes.
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowVisitorVisualization implements ezcWorkflowVisitor
{
    /**
     * Container to hold the properties.
     *
     * @var array(string=>mixed)
     */
    protected $properties = array(
      'colorHighlighted' => '#cc0000',
      'colorNormal'      => '#2e3436'
    );

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
     * Holds the name of the workflow.
     *
     * @var string
     */
    protected $workflowName = 'Workflow';

    /**
     * Holds the nodes that are to be highlighted.
     *
     * @var array
     */
    protected $highlightedNodes = array();

    /**
     * Holds the workflow variables.
     *
     * @var array
     */
    protected $workflowVariables = array();

    /**
     * Constructor.
     *
     * @param array $highlightedNodes Array of nodes that should be highlighted
     * @param array $workflowVariables Array of workflow variables
     */
    public function __construct( array $highlightedNodes = array(), array $workflowVariables = array() )
    {
        $this->highlightedNodes  = $highlightedNodes;
        $this->workflowVariables = $workflowVariables;
    }

    /**
     * Property read access.
     *
     * @throws ezcBasePropertyNotFoundException 
     *         If the the desired property is not found.
     * 
     * @param string $propertyName Name of the property.
     * @return mixed Value of the property or null.
     * @ignore
     */
    public function __get( $propertyName )
    {
        switch ( $propertyName ) 
        {
            case 'colorHighlighted':
            case 'colorNormal':
                return $this->properties[$propertyName];
        }

        throw new ezcBasePropertyNotFoundException( $propertyName );
    }

    /**
     * Property write access.
     * 
     * @param string $propertyName Name of the property.
     * @param mixed $val  The value for the property.
     *
     * @throws ezcBaseValueException 
     *         If the value for the property colorHighlighted is not a string.
     * @throws ezcBaseValueException 
     *         If the value for the property colorNormal is not a string.
     * @ignore
     */
    public function __set( $propertyName, $val )
    {
        switch ( $propertyName ) 
        {
            case 'colorHighlighted':
            case 'colorNormal':
                if ( !is_string( $val ) )
                {
                    throw new ezcBaseValueException( $propertyName, $val, 'string' );
                }

                $this->properties[$propertyName] = $val;

                return;
        }

        throw new ezcBasePropertyNotFoundException( $propertyName );
    }
 
    /**
     * Property isset access.
     * 
     * @param string $propertyName Name of the property.
     * @return bool True is the property is set, otherwise false.
     * @ignore
     */
    public function __isset( $propertyName )
    {
        switch ( $propertyName )
        {
            case 'colorHighlighted':
            case 'colorNormal':
                return true;
        }

        return false;
    }

    /**
     * Visits the node and sets the the member variables according to the node
     * type and contents.
     *
     * @param ezcWorkflowVisitable $visitable
     * @return boolean
     */
    public function visit( ezcWorkflowVisitable $visitable )
    {
        if ( $visitable instanceof ezcWorkflow )
        {
            $this->workflowName = $visitable->name;

            // The following line of code is not a no-op. It triggers the
            // ezcWorkflow::__get() method, thus initializing the respective
            // ezcWorkflowVisitorNodeCollector object.
            $visitable->nodes;
        }

        if ( $visitable instanceof ezcWorkflowNode )
        {
            $id = $visitable->getId();

            if ( isset( $this->visited[$id] ) )
            {
                return false;
            }

            $this->visited[$id] = true;

            if ( in_array( $id, $this->highlightedNodes ) )
            {
                $color = $this->properties['colorHighlighted'];
            }
            else
            {
                $color = $this->properties['colorNormal'];
            }

            if ( !isset( $this->nodes[$id] ) )
            {
                $this->nodes[$id] = array(
                  'label' => $visitable->__toString(),
                  'color' => $color
                );
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
                        $label = ' [label="' . $condition->__toString() . '"]';
                    }
                }

                $outNodes[] = array( $outNode->getId(), $label );
            }

            $this->edges[$id] = $outNodes;
        }

        return true;
    }

    /**
     * Returns a the contents of a graphviz .dot file.
     *
     * @return boolean
     * @ignore
     */
    public function __toString()
    {
        $dot = 'digraph ' . $this->workflowName . " {\n";

        foreach ( $this->nodes as $key => $data )
        {
            $dot .= sprintf(
              "node%s [label=\"%s\", color=\"%s\"]\n",
              $key,
              $data['label'],
              $data['color']
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

        if ( !empty( $this->workflowVariables ) )
        {
            $dot .= 'variables [shape=none, label=<<table>';

            foreach ( $this->workflowVariables as $name => $value )
            {
                $dot .= sprintf(
                  '<tr><td>%s</td><td>%s</td></tr>',

                  $name,
                  ezcWorkflowUtil::variableToString( $value )
                );
            }

            $dot .= "</table>>]\n";
        }

        return $dot . "}\n";
    }
}
?>
