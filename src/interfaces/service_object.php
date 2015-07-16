<?php
/**
 * File containing the ezcWorkflowServiceObject interface.
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
 * Interface for service objects that can be attached to
 * ezcWorkflowNodeAction nodes.
 *
 * @package Workflow
 * @version //autogen//
 */
interface ezcWorkflowServiceObject
{
    /**
     * Executes the business logic of this service object.
     *
     * Implementations can return true if the execution of the
     * service object was successful to resume the workflow and activate
     * the next node.
     *
     * Returning false will cause the workflow to be suspended and the service
     * object to be executed again on a later invokation.
     *
     * @param  ezcWorkflowExecution $execution
     * @return boolean
     */
    public function execute( ezcWorkflowExecution $execution );

    /**
     * Returns a textual representation of this service object.
     *
     * @return string
     */
    public function __toString();
}
?>
