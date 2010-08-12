<?php
/**
 * File containing the ezcWorkflowNodeEnd class.
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
 * An object of the ezcWorkflowNodeEnd class represents an end node of a workflow.
 *
 * A workflow must have at least one end node. The execution of the workflow ends
 * when an end node is reached.
 * Creating an object of the ezcWorkflow class automatically creates a default end node for the new
 * workflow. It can be accessed through the getEndNode() method.
 *
 * Incoming nodes: 1
 * Outgoing nodes: 0
 *
 * Example:
 * <code>
 * <?php
 * $workflow = new ezcWorkflow( 'Test' );
 * // build up your workflow here... result in $node
 * $node = ...
 * $workflow->startNode->addOutNode( ... some other node here ... );
 * $node->addOutNode( $workflow->endNode );
 * ?>
 * </code>
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeEnd extends ezcWorkflowNode
{
    /**
     * Constraint: The minimum number of outgoing nodes this node has to have
     * to be valid.
     *
     * @var integer
     */
    protected $minOutNodes = 0;

    /**
     * Constraint: The maximum number of outgoing nodes this node has to have
     * to be valid.
     *
     * @var integer
     */
    protected $maxOutNodes = 0;

    /**
     * Ends the execution of this workflow.
     *
     * @param ezcWorkflowExecution $execution
     * @return boolean true when the node finished execution,
     *                 and false otherwise
     * @ignore
     */
    public function execute( ezcWorkflowExecution $execution )
    {
        $execution->end( $this );

        return parent::execute( $execution );
    }
}
?>
