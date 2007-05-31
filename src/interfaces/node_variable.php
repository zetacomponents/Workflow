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
 * Base class for nodes that implement simple integer arithmetic.
 *
 * This class takes care of the configuration and setting and getting of
 * data. The data to manipulate is put into the $variable member. The manipulating
 * parameter is put into the member $value.
 *
 * Implementors must implement the method doExecute() and put the result of the
 * computation in $value member variable.
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
     * Contains the data to manipulate.
     *
     * @var mixed
     */
    protected $variable;

    /**
     * Contains the manipulation parameter (if any).
     *
     * @var mixed
     */
    protected $value = null;

    /**
     * Executes this node and returns true.
     *
     * Expects the configuration parameters 'name' the name of the workflow
     * variable to work on and the parameter 'value' the value to operate with
     * or the name of the workflow variable containing the value.
     *
     * @param ezcWorkflowExecution $execution
     * @return boolean
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
     * Implementors should perform the variable computation in this method.
     *
     * doExecute() is called automatically by execute().
     */
    abstract protected function doExecute();
}
?>
