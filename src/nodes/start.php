<?php
/**
 * File containing the ezcWorkflowNodeStart class.
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
 * An object of the ezcWorkflowNodeStart class represents the one and only
 * tart node of a workflow. The execution of the workflow starts here.
 *
 * Creating an object of the ezcWorkflow class automatically creates the start node
 * for the new workflow. It can be accessed through the $startNode property of the
 * workflow.
 *
 * Incoming nodes: 0
 * Outgoing nodes: 1
 *
 * Example:
 * <code>
 * <?php
 * $workflow = new ezcWorkflow( 'Test' );
 * $workflow->startNode->addOutNode( ....some other node here .. );
 * ?>
 * </code>
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeStart extends ezcWorkflowNode
{
    /**
     * Constraint: The minimum number of incoming nodes this node has to have
     * to be valid.
     *
     * @var integer
     */
    protected $minInNodes = 0;

    /**
     * Constraint: The maximum number of incoming nodes this node has to have
     * to be valid.
     *
     * @var integer
     */
    protected $maxInNodes = 0;

    /**
     * Activates the sole output node.
     *
     * @param ezcWorkflowExecution $execution
     * @return boolean true when the node finished execution,
     *                 and false otherwise
     * @ignore
     */
    public function execute( ezcWorkflowExecution $execution )
    {
        $this->outNodes[0]->activate(
          $execution,
          $this,
          $execution->startThread()
        );

        return parent::execute( $execution );
    }
}
?>
