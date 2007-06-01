<?php
/**
 * File containing the ezcWorkflowNodeInput class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * An object of the ezcWorkflowNodeInput class represents an input (from the application) node.
 *
 * When the node is reached, the workflow engine will suspend the workflow execution if the
 * specified input data is not available (first activation). While the workflow is suspended,
 * the application that embeds the workflow engine may supply the input data and resume the workflow
 * execution (second activation of the input node). Input data is stored in a workflow variable.
 *
 * Incomming nodes: 1
 * Outgoing nodes: 1
 *
 * @todo example, how do you specify the name of the input to react on?
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeInput extends ezcWorkflowNode
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

        $tmp = array();

        foreach ( $configuration as $key => $value )
        {
            if ( is_int( $key ) )
            {
                if ( !is_string( $value ) )
                {
                    throw new ezcBaseValueException(
                      'workflow variable name', $value, 'string'
                    );
                }

                $variable  = $value;
                $condition = new ezcWorkflowConditionIsAnything;
            }
            else
            {
                if ( !is_object( $value ) || !$value instanceof ezcWorkflowCondition )
                {
                    throw new ezcBaseValueException(
                      'workflow variable condition', $value, 'ezcWorkflowCondition'
                    );
                }

                $variable  = $key;
                $condition = $value;
            }

            $tmp[$variable] = $condition;
        }

        parent::__construct( $tmp );
    }

    /**
     * Executes this node.
     *
     * @param ezcWorkflowExecution $execution
     * @ignore
     */
    public function execute( ezcWorkflowExecution $execution )
    {
        $variables  = $execution->getVariables();
        $canExecute = true;

        foreach ( $this->configuration as $variable => $condition )
        {
            if ( !isset( $variables[$variable] ) )
            {
                $execution->addWaitingFor( $this, $variable, $condition );

                $canExecute = false;
            }
        }

        if ( $canExecute )
        {
            $this->activateNode( $execution, $this->outNodes[0] );

            return parent::execute( $execution );
        }
        else
        {
            return false;
        }
    }
}
?>
