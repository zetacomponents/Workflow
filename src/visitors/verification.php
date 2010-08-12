<?php
/**
 * File containing the ezcWorkflowVisitorVerification class.
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
 * An implementation of the ezcWorkflowVisitor interface that
 * verifies a workflow specification.
 *
 * This visitor should not be used directly but will be used by the
 * verify() method on the workflow.
 *
 * <code>
 * <?php
 * $workflow->verify();
 * ?>
 * </code>
 *
 * The verifier checks that:
 * - there is only one start node
 * - there is only one finally node
 * - each node satisfies the constraints of the respective node type
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowVisitorVerification extends ezcWorkflowVisitor
{
    /**
     * Holds the number of start nodes encountered during visiting.
     *
     * @var integer
     */
    protected $numStartNodes = 0;

    /**
     * Holds the number of finally nodes encountered during visiting.
     *
     * @var integer
     */
    protected $numFinallyNodes = 0;

    /**
     * Perform the visit.
     *
     * @param ezcWorkflowVisitable $visitable
     */
    protected function doVisit( ezcWorkflowVisitable $visitable )
    {
        if ( $visitable instanceof ezcWorkflow )
        {
            foreach ( $visitable->nodes as $node )
            {
                if ( $node instanceof ezcWorkflowNodeStart &&
                    !$node instanceof ezcWorkflowNodeFinally )
                {
                    $this->numStartNodes++;

                    if ( $this->numStartNodes > 1 )
                    {
                        throw new ezcWorkflowInvalidWorkflowException(
                          'A workflow may have only one start node.'
                        );
                    }
                }

                if ( $node instanceof ezcWorkflowNodeFinally )
                {
                    $this->numFinallyNodes++;

                    if ( $this->numFinallyNodes > 1 )
                    {
                        throw new ezcWorkflowInvalidWorkflowException(
                          'A workflow may have only one finally node.'
                        );
                    }
                }
            }
        }

        if ( $visitable instanceof ezcWorkflowNode )
        {
            $visitable->verify();
        }
    }
}
?>
