<?php
/**
 * File containing the ezcWorkflowNodeCancel class.
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
 * This node implements the Cancel Case workflow pattern.
 *
 * A complete process instance is removed. This includes currently executing
 * tasks, those which may execute at some future time and all sub-processes.
 * The process instance is recorded as having completed unsuccessfully.
 *
 * Incoming nodes: 1
 * Outgoing nodes: 0..1
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeCancel extends ezcWorkflowNodeEnd
{
    /**
     * Constraint: The minimum number of outgoing nodes this node has to have
     * to be valid. Set to false to disable this constraint.
     *
     * @var integer
     */
    protected $minOutNodes = 0;

    /**
     * Constraint: The maximum number of outgoing nodes this node has to have
     * to be valid. Set to false to disable this constraint.
     *
     * @var integer
     */
    protected $maxOutNodes = 1;

    /**
     * Cancels the execution of this workflow.
     *
     * @param ezcWorkflowExecution $execution
     * @param ezcWorkflowNode      $activatedFrom
     * @param int                  $threadId
     * @ignore
     */
    public function activate( ezcWorkflowExecution $execution, ezcWorkflowNode $activatedFrom = null, $threadId = 0 )
    {
        $execution->cancel( $this );
    }
}
?>
