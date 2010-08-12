<?php
/**
 * File containing the ezcWorkflowNodeDiscriminator class.
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
 * This node implements the Discriminator workflow pattern.
 *
 * The Discriminator workflow pattern can be applied when the assumption made for the
 * Simple Merge workflow pattern does not hold. It can deal with merge situations where multiple
 * incoming branches may run in parallel.
 * It activates its outgoing node after being activated by the first incoming branch and then waits
 * for all remaining branches to complete before it resets itself. After the reset the Discriminator
 * can be triggered again.
 *
 * Use Case Example: To improve response time, an action is delegated to several distributed
 * servers. The first response proceeds the flow, the other responses are ignored.
 *
 * Incoming nodes: 2..*
 * Outgoing nodes: 1
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
class ezcWorkflowNodeDiscriminator extends ezcWorkflowNodeMerge
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
        $this->setThreadId( $execution->getParentThreadId( $threadId ) );

        $numActivated = count( $this->state['threads'] );

        if ( $numActivated == 1 )
        {
            $this->activateNode( $execution, $this->outNodes[0] );
        }
        else if ( $numActivated == $execution->getNumSiblingThreads( $threadId ) )
        {
            parent::activate( $execution, $activatedFrom, $this->threadId );
        }

        $execution->endThread( $threadId );
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
        $this->initState();

        return parent::execute( $execution );
    }
}
?>
