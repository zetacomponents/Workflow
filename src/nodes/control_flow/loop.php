<?php
/**
 * File containing the ezcWorkflowNodeLoop class.
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
 * The Loop node type is a special type of conditional branch node that has two
 * incoming nodes instead of just one. It is used to conveniently express loops.
 *
 * Incoming nodes: 2..*
 * Outgoing nodes: 2..*
 *
 * The example below shows the equivalent of a for-loop that iterates the
 * variable i from 1 to 10:
 *
 * <code>
 * <?php
 * $workflow = new ezcWorkflow( 'IncrementingLoop' );
 *
 * $set      = new ezcWorkflowNodeVariableSet( array( 'i' => 1 ) );
 * $step     = new ezcWorkflowNodeVariableIncrement( 'i' );
 * $break    = new ezcWorkflowConditionVariable( 'i', new ezcWorkflowConditionIsEqual( 10 ) );
 * $continue = new ezcWorkflowConditionVariable( 'i', new ezcWorkflowConditionIsLessThan( 10 ) );
 *
 * $workflow->startNode->addOutNode( $set );
 *
 * $loop = new ezcWorkflowNodeLoop;
 * $loop->addInNode( $set );
 * $loop->addInNode( $step );
 *
 * $loop->addConditionalOutNode( $continue, $step );
 * $loop->addConditionalOutNode( $break, $workflow->endNode );
 * ?>
 * </code>
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeLoop extends ezcWorkflowNodeConditionalBranch
{
    /**
     * Constraint: The minimum number of incoming nodes this node has to have
     * to be valid. Set to false to disable this constraint.
     *
     * @var integer
     */
    protected $minInNodes = 2;

    /**
     * Constraint: The maximum number of incoming nodes this node has to have
     * to be valid. Set to false to disable this constraint.
     *
     * @var integer
     */
    protected $maxInNodes = false;

    /**
     * Constraint: The minimum number of outgoing nodes this node has to have
     * to be valid. Set to false to disable this constraint.
     *
     * @var integer
     */
    protected $minOutNodes = 2;

    /**
     * Constraint: The maximum number of outgoing nodes this node has to have
     * to be valid. Set to false to disable this constraint.
     *
     * @var integer
     */
    protected $maxOutNodes = false;

    /**
     * Whether or not to start a new thread for a branch.
     *
     * @var bool
     */
    protected $startNewThreadForBranch = false;
}
?>
