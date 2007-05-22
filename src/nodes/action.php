<?php
/**
 * File containing the ezcWorkflowNodeAction class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * An object of the ezcWorkflowNodeAction class represents an activity node holding business logic.
 *
 * When the node is reached during execution of the workflow, the business logic that is implemented
 * by the associated service object is executed.
 *
 * Incomming nodes: 1
 * Outgoing nodes: 1
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeAction extends ezcWorkflowNode
{
    /**
     * Constructor.
     *
     * @param mixed $configuration
     * @throws ezcWorkflowDefinitionException
     */
    public function __construct( $configuration )
    {
        if ( is_string( $configuration ) )
        {
            $configuration = array( 'class' => $configuration );
        }

        if ( !isset( $configuration['arguments'] ) )
        {
            $configuration['arguments'] = array();
        }

        parent::__construct( $configuration );
    }

    /**
     * Executes this node.
     *
     * @param ezcWorkflowExecution $execution
     */
    public function execute( ezcWorkflowExecution $execution )
    {
        $object = $this->createObject();
        $object->execute( $execution );

        $this->activateNode( $execution, $this->outNodes[0] );

        return parent::execute( $execution );
    }

    /**
     * Returns a textual representation of this node.
     *
     * @return string
     */
    public function __toString()
    {
        try {
            $object = $this->createObject();
        }
        catch ( ezcWorkflowExecutionException $e )
        {
            return $e->getMessage();
        }

        return (string)$object;
    }

    /**
     * @return ezcWorkflowServiceObject
     */
    protected function createObject()
    {
        if ( !class_exists( $this->configuration['class'], false ) )
        {
            throw new ezcWorkflowExecutionException(
              'Class not found.'
            );
        }

        $class = new ReflectionClass( $this->configuration['class'] );

        if ( !$class->implementsInterface( 'ezcWorkflowServiceObject' ) )
        {
            throw new ezcWorkflowExecutionException(
              'Class does not implement the ezcWorkflowServiceObject interface.'
            );
        }

        if ( !empty( $this->configuration['arguments'] ) )
        {
            return $class->newInstanceArgs( $this->configuration['arguments'] );
        }
        else
        {
            return $class->newInstance();
        }
    }
}
?>
