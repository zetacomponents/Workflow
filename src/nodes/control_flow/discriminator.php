<?php
/**
 * File containing the ezcWorkflowNodeDiscriminator class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * This node implements the Discriminator workflow pattern.
 *
 * The Discriminator workflow pattern can be applied when the assumption made for the
 * Simple Merge workflow pattern does not hold. It can deal with merge situations where multiple
 * incoming branches may run in parallel.
 * It activates its outgoing node after being activated by the first incoming branch and then waits
 * for all remaining branches to complete before it resets itself. After the reset the Discriminator
 * can be triggered again.
 *
 * Use Case Example: To improve response time, an action is delegated to several distributed
 * servers. The first response proceeds the flow, the other responses are ignored.
 *
 * Incomming nodes: 2..*
 * Outgoing nodes: 1
 *
 * @todo example
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeDiscriminator extends ezcWorkflowNodeMerge
{
    /**
     * Activate this node.
     *
     * @param ezcWorkflowExecution $execution
     * @param ezcWorkflowNode $activatedFrom
     * @param integer $threadId
     */
    public function activate( ezcWorkflowExecution $execution, ezcWorkflowNode $activatedFrom = null, $threadId = 0 )
    {
        $this->prepareActivate( $execution, $threadId );
        $this->setThreadId( $execution->getParentThreadId( $threadId ) );

        $numActivated = count( $this->state );

        if ( $numActivated == 1 )
        {
            $this->activateNode( $execution, $this->outNodes[0] );
        }
        else if ( $numActivated == $execution->getNumSiblingThreads( $threadId ) )
        {
            parent::activate( $execution, $activatedFrom, $this->threadId );
        }

        $execution->endThread( $threadId );
    }

    /**
     * Executes this node.
     *
     * @param ezcWorkflowExecution $execution
     */
    public function execute( ezcWorkflowExecution $execution )
    {
        $this->initState();

        return parent::execute( $execution );
    }
}
?>
