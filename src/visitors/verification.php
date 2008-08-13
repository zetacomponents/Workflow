<?php
/**
 * File containing the ezcWorkflowVisitorVerification class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
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
class ezcWorkflowVisitorVerification implements ezcWorkflowVisitor
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
     * Holds the id of each node that has been visited already.
     *
     * @var array
     */
    protected $visited = array();

    /**
     * Visits the node, checks contraints and calls verify on each node.
     *
     * Returns true if the node was verified. False if it was already
     * verified.
     *
     * @param ezcWorkflowVisitable $visitable
     * @throws ezcWorkflowInvalidWorkflowException
     * @return boolean
     */
    public function visit( ezcWorkflowVisitable $visitable )
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
            $id = $visitable->getId();

            if ( isset( $this->visited[$id] ) )
            {
                return false;
            }

            $this->visited[$id] = true;

            $visitable->verify();
        }

        return true;
    }
}
?>
