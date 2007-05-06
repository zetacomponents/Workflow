<?php
/**
 * File containing the ezcWorkflow class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Class representing a workflow.
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflow implements ezcWorkflowVisitable
{
    /**
     * The name of this workflow.
     *
     * @var string
     */
    protected $name = '';

    /**
     * Unique ID of this workflow.
     *
     * Only available when this workflow has been loaded from
     * or saved to the data storage.
     *
     * @var integer
     */
    protected $id = false;

    /**
     * Version of this workflow.
     *
     * Only available when this workflow has been loaded from
     * or saved to the data storage.
     *
     * @var integer
     */
    protected $version = false;

    /**
     * The variable handlers of this workflow.
     *
     * @var array
     */
    protected $variableHandlers = array();

    /**
     * The nodes of this workflow.
     *
     * @var array
     */
    protected $nodes = array();

    /**
     * The start node of this workflow.
     *
     * @var ezcWorkflowNodeStart
     */
    protected $startNode;

    /**
     * The default end node of this workflow.
     *
     * @var ezcWorkflowNodeEnd
     */
    protected $endNode;

    /**
     * A workflow definition storage handler.
     *
     * @var ezcWorkflowDefinition
     */
    protected $definitionHandler = null;

    /**
     * Constructor.
     *
     * @param string               $name      The name of the workflow.
     * @param ezcWorkflowNodeStart $startNode The start node of the workflow.
     * @param ezcWorkflowNodeEnd   $endNode   The default end node of the workflow.
     */
    public function __construct( $name, ezcWorkflowNodeStart $startNode = null, ezcWorkflowNodeEnd $endNode = null )
    {
        $this->setName( $name );

        // Create a new ezcWorkflowNodeStart object, if necessary.
        if ( $startNode === null )
        {
            $this->startNode = new ezcWorkflowNodeStart;
        }
        else
        {
            $this->startNode = $startNode;
        }

        $this->addNode( $this->startNode );

        // Create a new ezcWorkflowNodeEnd object, if necessary.
        if ( $endNode === null )
        {
            $this->endNode = new ezcWorkflowNodeEnd;
        }
        else
        {
            $this->endNode = $endNode;
        }

        $this->addNode( $this->endNode );
    }

    /**
     * Adds a node to this workflow.
     *
     * @param ezcWorkflowNode $node The node to be added.
     * @return boolean true when the node was added, false otherwise.
     */
    public function addNode( ezcWorkflowNode $node )
    {
        // Only add node if it has not been added before.
        if ( ezcWorkflowUtil::findObject( $this->nodes, $node ) !== false )
        {
            return false;
        }

        $this->nodes[] = $node;
        $node->setWorkflow( $this );

        return true;
    }

    /**
     * Returns this workflow's nodes.
     *
     * @return array The nodes of this workflow.
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * Returns this workflow's start node.
     *
     * @return ezcWorkflowNodeStart The start node of this workflow.
     */
    public function getStartNode()
    {
        return $this->startNode;
    }

    /**
     * Returns this workflow's default end node.
     *
     * @return ezcWorkflowNodeEnd The default end node of this workflow.
     */
    public function getEndNode()
    {
        return $this->endNode;
    }

    /**
     * Returns true when the workflow requires user interaction
     * (ie. when it contains ezcWorkflowNodeInput nodes)
     * and false otherwise.
     *
     * @return boolean true when the workflow is interactive, false otherwise.
     */
    public function isInteractive()
    {
        foreach ( $this->nodes as $node )
        {
            if ( $node instanceof ezcWorkflowNodeInput )
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns true when the workflow has sub workflows
     * (ie. when it contains ezcWorkflowNodeSubWorkflow nodes)
     * and false otherwise.
     *
     * @return boolean true when the workflow has sub workflows, false otherwise.
     */
    public function hasSubWorkflows()
    {
        foreach ( $this->nodes as $node )
        {
            if ( $node instanceof ezcWorkflowNodeSubWorkflow )
            {
                return true;
            }
        }

        return false;
    }

    /**
     * @return ezcWorkflowDefinition
     */
    public function getDefinition()
    {
        return $this->definitionHandler;
    }

    /**
     * @param ezcWorkflowDefinition $definitionHandler
     */
    public function setDefinition( ezcWorkflowDefinition $definitionHandler )
    {
        $this->definitionHandler = $definitionHandler;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId( $id )
    {
        $this->id = $id;
    }

    /**
     * @return integer
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param integer $version
     */
    public function setVersion( $version )
    {
        $this->version = $version;
    }

    /**
     * Verifies the specification of this workflow.
     *
     * @throws ezcWorkflowInvalidDefinitionException if the specification of this workflow is not correct.
     */
    public function verify()
    {
        $verifier = new ezcWorkflowVisitorVerification;

        $this->accept( $verifier );

        $verifier->verify();
    }

    /**
     * @param ezcWorkflowVisitor $visitor
     */
    public function accept( ezcWorkflowVisitor $visitor )
    {
        $visitor->visit( $this );
        $this->startNode->accept( $visitor );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @throws ezcBaseValueException
     */
    public function setName( $name )
    {
        if ( is_string( $name ) )
        {
            $this->name = $name;
        }
        else
        {
            throw new ezcBaseValueException(
              'name', $name, 'string'
            );
        }
    }

    /**
     * Adds a handler for a variable.
     *
     * @param string $variableName
     * @param string $className
     * @throws ezcWorkflowInvalidDefinitionException
     */
    public function addVariableHandler( $variableName, $className )
    {
        if ( class_exists( $className, false ) )
        {
            $class = new ReflectionClass( $className );

            if ( $class->implementsInterface( 'ezcWorkflowVariableHandler' ) )
            {
                $this->variableHandlers[$variableName] = $className;
            }
            else
            {
                throw new ezcWorkflowInvalidDefinitionException(
                  'Class does not implement the ezcWorkflowVariableHandler interface.'
                );
            }
        }
        else
        {
            throw new ezcWorkflowInvalidDefinitionException(
                'Class not found.'
            );
        }
    }

    /**
     * Removes a handler for a variable.
     *
     * @param string $variableName
     * @return boolean
     */
    public function removeVariableHandler( $variableName )
    {
        if ( isset( $this->variableHandlers[$variableName] ) )
        {
            unset( $this->variableHandlers[$variableName] );
            return true;
        }

        return false;
    }

    /**
     * Sets handlers for multiple variables.
     *
     * @param array $variableHandlers
     */
    public function setVariableHandlers( Array $variableHandlers )
    {
        $this->variableHandlers = array();

        foreach ( $variableHandlers as $variableName => $className )
        {
            $this->addVariableHandler( $variableName, $className );
        }
    }

    /**
     * Returns the variable handlers.
     *
     * @return array
     */
    public function getVariableHandlers()
    {
        return $this->variableHandlers;
    }
}
?>
