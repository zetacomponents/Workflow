<?php
/**
 * File containing the ezcWorkflowNodeParallelSplit class.
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
 * This node implements the Parallel Split workflow pattern.
 *
 * The Parallel Split workflow pattern divides one thread of execution
 * unconditionally into multiple parallel threads of execution.
 *
 * Use Case Example: After the credit card specified by the customer has been successfully
 * charged, the activities of sending a confirmation email and starting the shipping process can
 * be executed in parallel.
 *
 * Incoming nodes: 1
 * Outgoing nodes: 2..*
 *
 * This example creates a workflow that splits in two parallel threads which
 * are joined again using a ezcWorkflowNodeDiscriminator.
 *
 * <code>
 * <?php
 * $workflow = new ezcWorkflow( 'Test' );
 *
 * $split = new ezcWorkflowNodeParallelSplit();
 * $workflow->startNode->addOutNode( $split );
 * $nodeExec1 = ....; // create nodes for the first thread of execution here..
 * $nodeExec2 = ....; // create nodes for the second thread of execution here..
 *
 * $disc = new ezcWorkflowNodeDiscriminator();
 * $disc->addInNode( $nodeExec1 );
 * $disc->addInNode( $nodeExec2 );
 * $disc->addOutNode( $workflow->endNode );
 * ?>
 * </code>
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeParallelSplit extends ezcWorkflowNodeBranch
{
    /**
     * Activates all outgoing nodes.
     *
     * @param ezcWorkflowExecution $execution
     * @return boolean true when the node finished execution,
     *                 and false otherwise
     * @ignore
     */
    public function execute( ezcWorkflowExecution $execution )
    {
        return $this->activateOutgoingNodes( $execution, $this->outNodes );
    }
}
?>
