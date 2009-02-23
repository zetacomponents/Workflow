<?php
/**
 * File containing the ezcWorkflowVisitorReset class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * An implementation of the ezcWorkflowVisitor interface that
 * resets all the nodes of a workflow.
 *
 * This visitor should not be used directly but will be used by the
 * reset() method on the workflow.
 *
 * <code>
 * <?php
 * $workflow->reset();
 * ?>
 * </code>
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowVisitorReset implements ezcWorkflowVisitor
{
    /**
     * Holds the id of each node that has been visited already.
     *
     * @var array
     */
    protected $visited = array();

    /**
     * Visits the node and resets it.
     *
     * Returns true if the node was reset. False if it was already
     * reset.
     *
     * @param ezcWorkflowVisitable $visitable
     * @return boolean
     */
    public function visit( ezcWorkflowVisitable $visitable )
    {
        if ( $visitable instanceof ezcWorkflowNode )
        {
            $id = $visitable->getId();

            if ( isset( $this->visited[$id] ) )
            {
                return false;
            }

            $this->visited[$id] = true;

            $visitable->initState();
        }

        return true;
    }
}
?>
