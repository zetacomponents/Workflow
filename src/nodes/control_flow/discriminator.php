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

        $numActivated   = count( $this->state );
        $this->threadId = $execution->getParentThreadId( $threadId );

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
