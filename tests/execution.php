<?php
/**
 * File containing the ezcWorkflowTestExecution class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

ezcTestRunner::addFileToFilter( __FILE__ );

/**
 * Workflow execution engine for testing workflows.
 *
 * @package Workflow
 * @subpackage Tests
 * @version //autogen//
 */
class ezcWorkflowTestExecution extends ezcWorkflowExecutionNonInteractive
{
    /**
     * @var array
     */
    protected $inputVariables = array();

    /**
     * @var array
     */
    protected $inputVariablesForSubWorkflow = array();

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
        return parent::__get( $propertyName );
    }

    /**
     * Property write access.
     * 
     * @param string $propertyName Name of the property.
     * @param mixed $val  The value for the property.
     *
     * @throws ezcBaseValueException 
     *         If a the value for the property definitionHandler is not an
     *         instance of ezcWorkflowDefinition.
     * @throws ezcBaseValueException 
     *         If a the value for the property workflow is not an instance of
     *         ezcWorkflow.
     * @ignore
     */
    public function __set( $propertyName, $val )
    {
        if ( $propertyName == 'workflow' )
        {
            if ( !( $val instanceof ezcWorkflow ) )
            {
                throw new ezcBaseValueException( $propertyName, $val, 'ezcWorkflow' );
            }

            $this->properties['workflow'] = $val;

            return;
        }
        else
        {
            return parent::__set( $propertyName, $val );
        }
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
        return parent::__isset( $propertyName );
    }

    /**
     * Sets an input variable.
     *
     * @param  string $name
     * @param  mixed  $value
     */
    public function setInputVariable( $name, $value )
    {
        $this->inputVariables[$name] = $value;
    }

    /**
     * Sets an input variable for a sub workflow.
     *
     * @param  string $name
     * @param  mixed  $value
     */
    public function setInputVariableForSubWorkflow( $name, $value )
    {
        $this->inputVariablesForSubWorkflow[$name] = $value;
    }

    /**
     * Start workflow execution.
     *
     * @param  integer $parentId
     */
    protected function doStart( $parentId )
    {
    }

    /**
     * Suspend workflow execution.
     */
    public function suspend()
    {
        parent::suspend();

        PHPUnit_Framework_Assert::assertFalse( $this->hasEnded() );
        PHPUnit_Framework_Assert::assertFalse( $this->isResumed() );
        PHPUnit_Framework_Assert::assertTrue( $this->isSuspended() );

        $inputData  = array();
        $waitingFor = $this->getWaitingFor();

        foreach ( $this->inputVariables as $name => $value )
        {
            if ( isset( $waitingFor[$name] ) )
            {
                $inputData[$name] = $value;
            }
        }

        if ( empty( $inputData ) )
        {
            throw new ezcWorkflowExecutionException(
              'Workflow is waiting for input data that has not been mocked.'
            );
        }

        $this->resume( false, $inputData );
    }

    /**
     * Suspend workflow execution.
     */
    protected function doSuspend()
    {
        parent::doSuspend();
    }

    /**
     * Resume workflow execution.
     *
     * @param integer $executionId  ID of the execution to resume.
     */
    protected function doResume( $executionId )
    {
        parent::doResume( $executionId );
    }

    /**
     * End workflow execution.
     */
    protected function doEnd()
    {
        parent::doEnd();
    }

    /**
     * Returns a new execution object for a sub workflow.
     *
     * @param  integer $id
     * @return ezcWorkflowExecution
     */
    protected function doGetSubExecution( $id = NULL )
    {
        parent::doGetSubExecution( $id );

        $execution = new ezcWorkflowTestExecution;

        foreach ( $this->inputVariablesForSubWorkflow as $name => $value )
        {
            $execution->setInputVariable( $name, $value );
        }

        if ( $id !== NULL )
        {
            $execution->resume( $id );
        }

        return $execution;
    }
}
?>
