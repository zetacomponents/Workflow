<?php
/**
 * File containing the ezcWorkflowVisitor class.
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
 * Base class for visitor implementations that want to process
 * a workflow using the Visitor design pattern.
 *
 * visit() is called on each of the nodes in the workflow in a top-down,
 * depth-first fashion.
 *
 * Start the processing of the workflow by calling accept() on the workflow
 * passing the visitor object as the sole parameter.
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowVisitor implements Countable
{
    /**
     * Holds the visited nodes.
     *
     * @var SplObjectStorage
     */
    protected $visited;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->visited = new SplObjectStorage;
    }

    /**
     * Returns the number of visited nodes.
     *
     * @return integer
     */
    public function count()
    {
        return count( $this->visited );
    }

    /**
     * Visit the $visitable.
     *
     * Each node in the graph is visited once.
     *
     * @param ezcWorkflowVisitable $visitable
     * @return bool
     */
    public function visit( ezcWorkflowVisitable $visitable )
    {
        if ( $visitable instanceof ezcWorkflowNode )
        {
            if ( $this->visited->contains( $visitable ) )
            {
                return false;
            }

            $this->visited->attach( $visitable );
        }

        $this->doVisit( $visitable );

        return true;
    }

    /**
     * Perform the visit.
     *
     * @param ezcWorkflowVisitable $visitable
     */
    protected function doVisit( ezcWorkflowVisitable $visitable )
    {
    }
}
?>
