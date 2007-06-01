<?php
/**
 * File containing the ezcWorkflowNodeVariableSet class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * An object of the ezcWorkflowNodeVariableSet class sets the specified workflow variable to
 * a given value.
 *
 * <code>
 *  $set = new ezcWorkflowNodeVariableSet ( array ( 'variable name' = > $value ) );
 * </code>
 *
 * Incomming nodes: 1
 * Outgoing nodes: 1
 *
 * @todo is this only meant for static use (e.g value defined at workflow build up) or also for dynamic use?
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeVariableSet extends ezcWorkflowNode
{
    /**
     * Constructor.
     *
     * @param mixed $configuration
     * @throws ezcBaseValueException
     */
    public function __construct( $configuration = '' )
    {
        if ( !is_array( $configuration ) )
        {
            throw new ezcBaseValueException(
              'configuration', $configuration, 'array'
            );
        }

        parent::__construct( $configuration );
    }

    /**
     * Executes this node.
     *
     * @param ezcWorkflowExecution $execution
     * @ignore
     */
    public function execute( ezcWorkflowExecution $execution )
    {
        foreach ( $this->configuration as $variable => $value )
        {
            $execution->setVariable( $variable, $value );
        }

        $this->activateNode( $execution, $this->outNodes[0] );

        return parent::execute( $execution );
    }

    /**
     * Returns a textual representation of this node.
     *
     * @return string
     * @ignore
     */
    public function __toString()
    {
        $buffer = array();

        foreach ( $this->configuration as $variable => $value )
        {
            $buffer[] = $variable . ' = ' . ezcWorkflowUtil::variableToString( $value );
        }

        return implode( ', ', $buffer );
    }
}
?>
