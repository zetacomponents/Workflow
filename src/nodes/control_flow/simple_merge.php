<?php
/**
 * File containing the ezcWorkflowNodeSimpleMerge class.
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
 * This node implements the Simple Merge (XOR-Join) workflow pattern.
 *
 * The Simple Merge workflow pattern is to be used to merge the possible paths that are defined
 * by a preceding Exclusive Choice. It is assumed that of these possible paths exactly one is
 * taken and no synchronization takes place.
 *
 * Use Case Example: After the payment has been performed by either credit card or bank
 * transfer, the order can be processed further.
 *
 * Incoming nodes: 2..*
 * Outgoing nodes: 1
 *
 * This example displays how you can use a simple merge to tie together two different
 * execution paths from an exclusive choice into one.
 *
 * <code>
 * <?php
 * $workflow = new ezcWorkflow( 'Test' );
 *
 * // wait for input into the workflow variable value.
 * $input = new ezcWorkflowNodeInput( array( 'value' => new ezcWorkflowConditionIsInt ) );
 * $workflow->startNode->addOutNode( $input );
 *
 * // create the exclusive choice branching node
 * $choice = new ezcWorkflowNodeExclusiveChoice;
 * $intput->addOutNode( $choice );
 *
 * $branch1 = ....; // create nodes for the first branch of execution here..
 * $branch2 = ....; // create nodes for the second branch of execution here..
 *
 * // add the outnodes and set the conditions on the exclusive choice
 * $choice->addConditionalOutNode( new ezcWorkflowConditionVariable( 'value',
 *                                                                  new ezcWorkflowConditionGreatherThan( 10 ) ),
 *                                $branch1 );
 * $choice->addConditionalOutNode( new ezcWorkflowConditionVariable( 'value',
 *                                                                  new ezcWorkflowConditionLessThan( 11 ) ),
 *                                $branch2 );
 *
 * // Merge the two branches together and continue execution.
 * $merge = new ezcWorkflowNodeSimpleMerge();
 * $merge->addInNode( $branch1 );
 * $merge->addInNode( $branch2 );
 * $merge->addOutNode( $workflow->endNode );
 * ?>
 * </code>
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeSimpleMerge extends ezcWorkflowNodeMerge
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
        $parentThreadId = $execution->getParentThreadId( $threadId );

        if ( empty( $this->state['threads'] ) )
        {
            $this->state['threads'][] = $threadId;

            parent::activate( $execution, $activatedFrom, $parentThreadId );
        }
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
        return $this->doMerge( $execution );
    }
}
?>
