<?php
/**
 * File containing the ezcWorkflowVisitorNodeCollector class.
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
 * Collects all the nodes in a workflow in an array.
 *
 * @package Workflow
 * @version //autogen//
 * @ignore
 */
class ezcWorkflowVisitorNodeCollector extends ezcWorkflowVisitor
{
    /**
     * Holds the start node object.
     *
     * @var ezcWorkflowNodeStart
     */
    protected $startNode;

    /**
     * Holds the default end node object.
     *
     * @var ezcWorkflowNodeEnd
     */
    protected $endNode;

    /**
     * Holds the finally node object.
     *
     * @var ezcWorkflowNodeFinally
     */
    protected $finallyNode;

    /**
     * Flag that indicates whether the finally node has been visited.
     *
     * @var boolean
     */
    protected $finallyNodeVisited = false;

    /**
     * Holds the visited nodes.
     *
     * @var array(integer=>ezcWorkflowNode)
     */
    protected $nodes = array();

    /**
     * Holds the sequence of node ids.
     *
     * @var integer
     */
    protected $nextId = 0;

    /**
     * Flag that indicates whether the node list has been sorted.
     *
     * @var boolean
     */
    protected $sorted = false;

    /**
     * Constructor.
     *
     * @param ezcWorkflow $workflow
     */
    public function __construct( ezcWorkflow $workflow )
    {
        parent::__construct();
        $workflow->accept( $this );
    }

    /**
     * Perform the visit.
     *
     * @param ezcWorkflowVisitable $visitable
     */
    protected function doVisit( ezcWorkflowVisitable $visitable )
    {
        if ( $visitable instanceof ezcWorkflow )
        {
            $visitable->startNode->setId( ++$this->nextId );
            $this->startNode = $visitable->startNode;

            $visitable->endNode->setId( ++$this->nextId );
            $this->endNode = $visitable->endNode;

            if ( count( $visitable->finallyNode->getOutNodes() ) > 0 )
            {
                $this->finallyNode = $visitable->finallyNode;
                $visitable->finallyNode->setId( ++$this->nextId );
            }
        }

        else if ( $visitable instanceof ezcWorkflowNode )
        {
            if ( $visitable !== $this->startNode &&
                 $visitable !== $this->endNode &&
                 $visitable !== $this->finallyNode )
            {
                $id = ++$this->nextId;
                $visitable->setId( $id );
            }
            else
            {
                $id = $visitable->getId();
            }

            $this->nodes[$id] = $visitable;
        }
    }

    /**
     * Returns the collected nodes.
     *
     * @return array
     */
    public function getNodes()
    {
        if ( $this->finallyNode !== null && !$this->finallyNodeVisited )
        {
            $this->finallyNode->accept( $this );
            $this->finallyNode = true;
        }

        if ( !$this->sorted )
        {
            ksort( $this->nodes );
            $this->sorted = true;
        }

        return $this->nodes;
    }
}
?>
