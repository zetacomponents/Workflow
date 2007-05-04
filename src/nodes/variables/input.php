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
 * This node asks (the user) for input.
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeInput extends ezcWorkflowNode
{
    /**
     * Constructor.
     *
     * @param mixed $configuration
     * @throws InvalidArgumentException
     */
    public function __construct( $configuration = '' )
    {
        if ( !is_array( $configuration ) )
        {
            throw new InvalidArgumentException;
        }

        $tmp = array();

        foreach ( $configuration as $key => $value )
        {
            if ( is_int( $key ) )
            {
                if ( !is_string( $value ) )
                {
                    throw new InvalidArgumentException;
                }

                $variable  = $value;
                $condition = new ezcWorkflowConditionIsAnything;
            }
            else
            {
                if ( !is_object( $value ) || !$value instanceof ezcWorkflowCondition )
                {
                    throw new InvalidArgumentException;
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
