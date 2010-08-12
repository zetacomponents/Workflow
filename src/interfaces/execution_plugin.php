<?php
/**
 * File containing the ezcWorkflowExecutionPlugin class.
 *
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 * 
 *   http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 *
 * @package Workflow
 * @version //autogen//
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

/**
 * Abstract base class for workflow execution engine plugins.
 *
 * @package Workflow
 * @version //autogen//
 */
abstract class ezcWorkflowExecutionPlugin
{
    /**
     * Called after an execution has been started.
     *
     * @param ezcWorkflowExecution $execution
     */
    public function afterExecutionStarted( ezcWorkflowExecution $execution )
    {
    // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd

    /**
     * Called after an execution has been suspended.
     *
     * @param ezcWorkflowExecution $execution
     */
    public function afterExecutionSuspended( ezcWorkflowExecution $execution )
    {
    // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd

    /**
     * Called after an execution has been resumed.
     *
     * @param ezcWorkflowExecution $execution
     */
    public function afterExecutionResumed( ezcWorkflowExecution $execution )
    {
    // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd

    /**
     * Called after an execution has been cancelled.
     *
     * @param ezcWorkflowExecution $execution
     */
    public function afterExecutionCancelled( ezcWorkflowExecution $execution )
    {
    // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd

    /**
     * Called after an execution has successfully ended.
     *
     * @param ezcWorkflowExecution $execution
     */
    public function afterExecutionEnded( ezcWorkflowExecution $execution )
    {
    // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd

    /**
     * Called before a node is activated.
     *
     * @param ezcWorkflowExecution $execution
     * @param ezcWorkflowNode      $node
     * @return bool true, when the node should be activated, false otherwise
     */
    public function beforeNodeActivated( ezcWorkflowExecution $execution, ezcWorkflowNode $node )
    {
    // @codeCoverageIgnoreStart
        return true;
    }
    // @codeCoverageIgnoreEnd

    /**
     * Called after a node has been activated.
     *
     * @param ezcWorkflowExecution $execution
     * @param ezcWorkflowNode      $node
     */
    public function afterNodeActivated( ezcWorkflowExecution $execution, ezcWorkflowNode $node )
    {
    // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd

    /**
     * Called after a node has been executed.
     *
     * @param ezcWorkflowExecution $execution
     * @param ezcWorkflowNode      $node
     */
    public function afterNodeExecuted( ezcWorkflowExecution $execution, ezcWorkflowNode $node )
    {
    // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd

    /**
     * Called after a new thread has been started.
     *
     * @param ezcWorkflowExecution $execution
     * @param int                  $threadId
     * @param int                  $parentId
     * @param int                  $numSiblings
     */
    public function afterThreadStarted( ezcWorkflowExecution $execution, $threadId, $parentId, $numSiblings )
    {
    // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd

    /**
     * Called after a thread has ended.
     *
     * @param ezcWorkflowExecution $execution
     * @param int                  $threadId
     */
    public function afterThreadEnded( ezcWorkflowExecution $execution, $threadId )
    {
    // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd

    /**
     * Called before a variable is set.
     *
     * @param  ezcWorkflowExecution $execution
     * @param  string               $variableName
     * @param  mixed                $value
     * @return mixed the value the variable should be set to
     */
    public function beforeVariableSet( ezcWorkflowExecution $execution, $variableName, $value )
    {
    // @codeCoverageIgnoreStart
        return $value;
    }
    // @codeCoverageIgnoreEnd

    /**
     * Called after a variable has been set.
     *
     * @param ezcWorkflowExecution $execution
     * @param string               $variableName
     * @param mixed                $value
     */
    public function afterVariableSet( ezcWorkflowExecution $execution, $variableName, $value )
    {
    // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd

    /**
     * Called before a variable is unset.
     *
     * @param  ezcWorkflowExecution $execution
     * @param  string               $variableName
     * @return bool true, when the variable should be unset, false otherwise
     */
    public function beforeVariableUnset( ezcWorkflowExecution $execution, $variableName )
    {
    // @codeCoverageIgnoreStart
        return true;
    }
    // @codeCoverageIgnoreEnd

    /**
     * Called after a variable has been unset.
     *
     * @param ezcWorkflowExecution $execution
     * @param string               $variableName
     */
    public function afterVariableUnset( ezcWorkflowExecution $execution, $variableName )
    {
    // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd
}
?>
