<?php
/**
 * File containing the ezcWorkflowNodeVariable class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Base class for nodes that modify variables.
 *
 * @package Workflow
 * @version //autogen//
 */
abstract class ezcWorkflowNodeVariable extends ezcWorkflowNode
{
    /**
     * Array with name and value of/for the workflow variable to be set.
     *
     * @var array
     */
    protected $configuration;

    /**
     * @var mixed
     */
    protected $variable;

    /**
     * @var mixed
     */
    protected $value = null;

    /**
     * Executes this node.
     *
     * @param ezcWorkflowExecution $execution
     */
    public function execute( ezcWorkflowExecution $execution )
    {
        if ( is_array( $this->configuration ) )
        {
            $variableName = $this->configuration['name'];
        }
        else
        {
            $variableName = $this->configuration;
        }

        $this->variable = $execution->getVariable( $variableName );

        if ( !is_numeric( $this->variable ) )
        {
            throw new ezcWorkflowExecutionException(
                sprintf(
                'Variable "%s" is not a number.',
                $variableName
                )
            );
        }

        if ( is_numeric( $this->configuration['value'] ) )
        {
            $this->value = $this->configuration['value'];
        }

        else if ( is_string( $this->configuration['value'] ) )
        {
            try
            {
                $value = $execution->getVariable( $this->configuration['value'] );

                if ( is_numeric( $value ) )
                {
                    $this->value = $value;
                }
            }
            catch ( ezcWorkflowExecutionException $e )
            {
            }
        }

        if ( $this->value === null )
        {
            throw new ezcWorkflowExecutionException( 'Illegal argument.' );
        }

        $this->doExecute();

        $execution->setVariable( $variableName, $this->variable );
        $this->activateNode( $execution, $this->outNodes[0] );

        return parent::execute( $execution );
    }

    /**
     * Perform variable modification.
     */
    abstract protected function doExecute();
}
?>
