<?php
/**
 * File containing the ezcWorkflowNodeMerge class.
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
 * Base class for nodes that merge multiple threads of execution.
 *
 * @package Workflow
 * @version //autogen//
 */
abstract class ezcWorkflowNodeMerge extends ezcWorkflowNode
{
    /**
     * Constraint: The minimum number of incoming nodes this node has to have
     * to be valid.
     *
     * @var integer
     */
    protected $minInNodes = 2;

    /**
     * Constraint: The maximum number of incoming nodes this node has to have
     * to be valid.
     *
     * @var integer
     */
    protected $maxInNodes = false;

    /**
     * The state of this node.
     *
     * @var array
     */
    protected $state;

    /**
     * Prepares this node for activation.
     *
     * @param ezcWorkflowExecution $execution
     * @param int $threadId
     * @throws ezcWorkflowExecutionException
     */
    protected function prepareActivate( ezcWorkflowExecution $execution, $threadId = 0 )
    {
        $parentThreadId = $execution->getParentThreadId( $threadId );

        if ( $this->state['siblings'] == -1 )
        {
            $this->state['siblings'] = $execution->getNumSiblingThreads( $threadId );
        }
        else
        {
            foreach ( $this->state['threads'] as $oldThreadId )
            {
                if ( $parentThreadId != $execution->getParentThreadId( $oldThreadId ) )
                {
                    throw new ezcWorkflowExecutionException(
                      'Cannot synchronize threads that were started by different branches.'
                    );
                }
            }
        }

        $this->state['threads'][] = $threadId;
    }

    /**
     * Performs the merge by ending the incoming threads and
     * activating the outgoing node.
     *
     * @param ezcWorkflowExecution $execution
     * @return boolean true when the node finished execution,
     *                 and false otherwise
     */
    protected function doMerge( ezcWorkflowExecution $execution )
    {
        foreach ( $this->state['threads'] as $threadId )
        {
            $execution->endThread( $threadId );
        }

        $this->activateNode( $execution, $this->outNodes[0] );
        $this->initState();

        return parent::execute( $execution );
    }

    /**
     * Initializes the state of this node.
     *
     * @ignore
     */
    public function initState()
    {
        parent::initState();

        $this->state = array( 'threads' => array(), 'siblings' => -1 );
    }
}
?>
