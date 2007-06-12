<?php
/**
 * File containing the ezcWorkflowNodeBranch class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Abstract base class for nodes that conditionally branch multiple threads of
 * execution.
 *
 * Most implementations only need to set the conditions for proper functioning.
 *
 * @package Workflow
 * @version //autogen//
 */
abstract class ezcWorkflowNodeConditionalBranch extends ezcWorkflowNodeBranch
{
    /**
     * Constraint: The minimum number of conditional outgoing nodes this node
     * has to have. Set to false to disable this constraint.
     *
     * @var integer
     */
    protected $minConditionalOutNodes = false;

    /**
     * Constraint: The maximum number of conditional outgoing nodes this node
     * may have. Set to false to disable this constraint.
     *
     * @var integer
     */
    protected $maxConditionalOutNodes = false;

    /**
     * Constraint: The minimum number of conditional outgoing nodes this node
     * has to activate. Set to false to disable this constraint.
     *
     * @var integer
     */
    protected $minActivatedConditionalOutNodes = false;

    /**
     * Constraint: The maximum number of conditional outgoing nodes this node
     * may activate. Set to false to disable this constraint.
     *
     * @var integer
     */
    protected $maxActivatedConditionalOutNodes = false;

    /**
     * Holds the conditions of the out nodes.
     *
     * The key is the position of the out node in the array of out nodes.
     *
     * @var array( 'int' => ezcWorkflowCondtion )
     */
    protected $configuration = array();


    /**
     * Adds the conditional outgoing node $outNode to this node with the condition $condition.
     *
     * @param ezcWorkflowCondition $condition
     * @param ezcWorkflowNode $outNode
     * @return ezcWorkflowNode
     */
    public function addConditionalOutNode( ezcWorkflowCondition $condition, ezcWorkflowNode $outNode )
    {
        $this->addOutNode( $outNode );
        $this->configuration[ezcWorkflowUtil::findObject( $this->outNodes, $outNode )] = $condition;

        return $this;
    }

    /**
     * Returns the condition for a conditional outgoing node
     * and false if the passed not is not a (unconditional)
     * outgoing node of this node.
     *
     * @param  ezcWorkflowNode $node
     * @return ezcWorkflowCondition
     * @ignore
     */
    public function getCondition( ezcWorkflowNode $node )
    {
        $keys    = array_keys( $this->outNodes );
        $numKeys = count( $keys );

        for ( $i = 0; $i < $numKeys; $i++ )
        {
            if ( $this->outNodes[$keys[$i]] === $node )
            {
                if ( isset( $this->configuration[$keys[$i]] ) )
                {
                    return $this->configuration[$keys[$i]];
                }
                else
                {
                    return false;
                }
            }
        }

        return false;
    }

    /**
     * Evaluates all the conditions, checks the constraints and activates any nodes that have
     * passed through both checks and condition evaluation.
     *
     * @param ezcWorkflowExecution $execution
     * @ignore
     */
    public function execute( ezcWorkflowExecution $execution )
    {
        $keys                            = array_keys( $this->outNodes );
        $numKeys                         = count( $keys );
        $nodesToStart                    = array();
        $numActivatedConditionalOutNodes = 0;

        for ( $i = 0; $i < $numKeys; $i++ )
        {
            if ( isset( $this->configuration[$keys[$i]] ) )
            {
                // Conditional outgoing node.
                if ( $this->configuration[$keys[$i]]->evaluate( $execution->getVariables() ) )
                {
                    $nodesToStart[] = $this->outNodes[$keys[$i]];
                    $numActivatedConditionalOutNodes++;
                }
            }
            else
            {
                // Unconditional outgoing node.
                $nodesToStart[] = $this->outNodes[$keys[$i]];
            }
        }

        if ( $this->minActivatedConditionalOutNodes !== false && $numActivatedConditionalOutNodes < $this->minActivatedConditionalOutNodes )
        {
            throw new ezcWorkflowExecutionException(
              'Node activates less conditional outgoing nodes than required.'
            );
        }

        if ( $this->maxActivatedConditionalOutNodes !== false && $numActivatedConditionalOutNodes > $this->maxActivatedConditionalOutNodes )
        {
            throw new ezcWorkflowExecutionException(
              'Node activates more conditional outgoing nodes than allowed.'
            );
        }

        return $this->activateOutgoingNodes( $execution, $nodesToStart );
    }

    /**
     * Checks this node's constraints.
     *
     * @throws ezcWorkflowInvalidWorkflowException if the constraints of this node are not met.
     */
    public function verify()
    {
        parent::verify();

        $numConditionalOutNodes = count( $this->configuration );

        if ( $this->minConditionalOutNodes !== false && $numConditionalOutNodes < $this->minConditionalOutNodes )
        {
            throw new ezcWorkflowInvalidWorkflowException(
              'Node has less conditional outgoing nodes than required.'
            );
        }

        if ( $this->maxConditionalOutNodes !== false && $numConditionalOutNodes > $this->maxConditionalOutNodes )
        {
            throw new ezcWorkflowInvalidWorkflowException(
              'Node has more conditional outgoing nodes than allowed.'
            );
        }
    }
}
?>
