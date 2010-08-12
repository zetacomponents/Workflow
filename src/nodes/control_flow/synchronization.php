<?php
/**
 * File containing the ezcWorkflowNodeSynchronization class.
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
 * This node implements the Synchronization (AND-Join) workflow pattern.
 *
 * The Synchronization workflow pattern synchronizes multiple parallel threads of execution
 * into a single thread of execution.
 *
 * Workflow execution continues once all threads of execution that are to be synchronized have
 * finished executing (exactly once).
 *
 * Use Case Example: After the confirmation email has been sent and the shipping process has
 * been completed, the order can be archived.
 *
 * Incoming nodes: 2..*
 * Outgoing nodes: 1
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeSynchronization extends ezcWorkflowNodeMerge
{
    /**
     * Activate this node.
     *
     * @param ezcWorkflowExecution $execution
     * @param ezcWorkflowNode $activatedFrom
     * @param int $threadId
     * @ignore
     */
    public function activate( ezcWorkflowExecution $execution, ezcWorkflowNode $activatedFrom = null, $threadId = 0 )
    {
        $this->prepareActivate( $execution, $threadId );
        parent::activate( $execution, $activatedFrom, $execution->getParentThreadId( $threadId ) );
    }

    /**
     * Executes this node.
     *
     * @param ezcWorkflowExecution $execution
     * @return boolean true when the node finished execution,
     *                 and false otherwise
     * @ignore
     */
    public function execute( ezcWorkflowExecution $execution )
    {
        if ( count( $this->state['threads'] ) == $this->state['siblings'] )
        {
            return $this->doMerge( $execution );
        }
        else
        {
            return false;
        }
    }
}
?>
