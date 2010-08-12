<?php
/**
 * File containing the ezcWorkflowExecutionNonInteractive class.
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
 * Workflow execution engine for non-interactive workflows.
 *
 * This workflow execution engine can only execute workflows that do not have
 * any Input and/or SubWorkflow nodes.
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowExecutionNonInteractive extends ezcWorkflowExecution
{
    /**
     * Property write access.
     *
     * @param string $propertyName Name of the property.
     * @param mixed $val  The value for the property.
     *
     * @throws ezcBaseValueException
     *         If a the value for the property definitionStorage is not an
     *         instance of ezcWorkflowDefinitionStorage.
     * @throws ezcBaseValueException
     *         If a the value for the property workflow is not an instance of
     *         ezcWorkflow.
     * @ignore
     */
    public function __set( $propertyName, $val )
    {
        if ( $val instanceof ezcWorkflow && ( $val->isInteractive() || $val->hasSubWorkflows() ) )
        {
            throw new ezcWorkflowExecutionException(
              'This executer can only execute workflows that have no Input and SubWorkflow nodes.'
            );
        }

        return parent::__set( $propertyName, $val );
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
    protected function doSuspend()
    {
    }

    /**
     * Resume workflow execution.
     */
    protected function doResume()
    {
    }

    /**
     * End workflow execution.
     */
    protected function doEnd()
    {
    }

    /**
     * Returns a new execution object for a sub workflow.
     *
     * @param  int $id
     * @return ezcWorkflowExecution
     */
    protected function doGetSubExecution( $id = null )
    {
    }
}
?>
