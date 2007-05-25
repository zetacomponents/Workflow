<?php
/**
 * File containing the ezcWorkflowVisitorVerification class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
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
 *  $workflow->verify();
 * </code>
 *
 * The verifyer checks that:
 * - there is only one start node
 * - all end nodes are reachable
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowVisitorVerification implements ezcWorkflowVisitor
{
    /**
     * @var integer
     */
    protected $numStartNodes = 0;

    /**
     * @var integer
     */
    protected $numEndNodes = 0;

    /**
     * @var integer
     */
    protected $numEndNodesReached = 0;

    /**
     * @var array
     */
    protected $visited = array();

    /**
     * @param ezcWorkflowVisitable $node
     * @throws ezcWorkflowInvalidDefinitionException
     */
    public function visit( ezcWorkflowVisitable $visitable )
    {
        foreach ( $this->visited as $visited )
        {
            if ( $visited === $visitable )
            {
                return false;
            }
        }

        $this->visited[] = $visitable;

        if ( $visitable instanceof ezcWorkflow )
        {
            foreach ( $visitable as $node )
            {
                if ( $node instanceof ezcWorkflowNodeStart )
                {
                    $this->numStartNodes++;

                    if ( $this->numStartNodes > 1 )
                    {
                        throw new ezcWorkflowInvalidDefinitionException(
                          'A workflow may have only one start node.'
                        );
                    }
                }

                if ( $node instanceof ezcWorkflowNodeEnd )
                {
                    $this->numEndNodes++;
                }
            }
        }

        if ( $visitable instanceof ezcWorkflowNode )
        {
            if ( $visitable instanceof ezcWorkflowNodeEnd )
            {
                $this->numEndNodesReached++;
            }

            $visitable->verify();
        }

        return true;
    }

    /**
     * @throws ezcWorkflowInvalidDefinitionException
     */
    public function verify()
    {
        if ( $this->numEndNodes > $this->numEndNodesReached )
        {
            throw new ezcWorkflowInvalidDefinitionException(
              'Not all end nodes are reachable.'
            );
        }
    }
}
?>
