<?php
/**
 * File containing the ezcWorkflowNodeBranch class.
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
 * Base class for nodes that branch multiple threads of execution.
 *
 * @package Workflow
 * @version //autogen//
 */
abstract class ezcWorkflowNodeBranch extends ezcWorkflowNode
{
    /**
     * Constraint: The minimum number of outgoing nodes this node has to have
     * to be valid.
     *
     * @var integer
     */
    protected $minOutNodes = 2;

    /**
     * Constraint: The maximum number of outgoing nodes this node has to have
     * to be valid.
     *
     * @var integer
     */
    protected $maxOutNodes = false;

    /**
     * Whether or not to start a new thread for a branch.
     *
     * @var bool
     */
    protected $startNewThreadForBranch = true;

    /**
     * Activates this node's outgoing nodes.
     *
     * @param ezcWorkflowExecution $execution
     * @param array                $nodes
     * @return boolean true when the node finished execution,
     *                 and false otherwise
     */
    protected function activateOutgoingNodes( ezcWorkflowExecution $execution, array $nodes )
    {
        $threadId           = $this->getThreadId();
        $numNodesToActivate = count( $nodes );

        foreach ( $nodes as $node )
        {
            if ( $this->startNewThreadForBranch )
            {
                $node->activate( $execution, $this, $execution->startThread( $threadId, $numNodesToActivate ) );
            }
            else
            {
                $node->activate( $execution, $this, $threadId );
            }
        }

        return parent::execute( $execution );
    }
}
?>
