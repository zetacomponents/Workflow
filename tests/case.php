<?php
/**
 * @package Workflow
 * @subpackage Tests
 * @version //autogentag//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once 'variable_handler.php';

ezcTestRunner::addFileToFilter( __FILE__ );

/**
 * @package Workflow
 * @subpackage Tests
 */
abstract class ezcWorkflowTestCase extends ezcTestCase
{
    protected $definition;
    protected $workflow;
    protected $startNode;
    protected $endNode;
    protected $actionNodeA;
    protected $actionNodeB;
    protected $actionNodeC;
    protected $branchNode;

    protected function setUp()
    {
        parent::setUp();

        $this->definition = new ezcWorkflowDefinitionXml(
          dirname( __FILE__ ) . '/data/'
        );

        if ( !class_exists( 'SOA', false ) )
        {
            $this->getMock( 'ezcWorkflowServiceObject', array(), array(), 'SOA' );
            $this->getMock( 'ezcWorkflowServiceObject', array(), array(), 'SOB' );
            $this->getMock( 'ezcWorkflowServiceObject', array(), array(), 'SOC' );
        }
    }

    protected function setUpEmptyWorkflow()
    {
        $this->workflow = new ezcWorkflow( 'Empty' );
        $this->setUpReferences();
    }

    protected function setUpStartEnd()
    {
        $this->workflow = new ezcWorkflow( 'StartEnd' );
        $this->setUpReferences();

        $this->startNode->addOutNode( $this->endNode );
    }

    protected function setUpStartEndVariableHandler()
    {
        $this->workflow = new ezcWorkflow( 'StartEndVariableHandler' );
        $this->setUpReferences();

        $this->startNode->addOutNode( $this->endNode );

        $this->workflow->addVariableHandler( 'foo', 'ezcWorkflowTestVariableHandler' );
    }

    protected function setUpStartInputEnd()
    {
        $this->workflow = new ezcWorkflow( 'StartInputEnd' );
        $this->setUpReferences();

        $inputNode = new ezcWorkflowNodeInput( array( 'variable' ) );

        $this->startNode->addOutNode( $inputNode );
        $this->endNode->addInNode( $inputNode );
    }

    protected function setUpStartSetUnsetEnd()
    {
        $this->workflow = new ezcWorkflow( 'StartSetUnsetEnd' );
        $this->setUpReferences();

        $set = new ezcWorkflowNodeVariableSet(
          array( 'x' => 1 )
        );

        $unset = new ezcWorkflowNodeVariableUnset( 'x' );

        $this->startNode->addOutNode( $set );
        $set->addOutNode( $unset );
        $unset->addOutNode( $this->endNode );
    }

    protected function setUpLoop( $direction )
    {
        if ( $direction == 'increment' )
        {
            $this->workflow = new ezcWorkflow( 'IncrementingLoop' );

            $start = 1;
            $step = new ezcWorkflowNodeVariableIncrement( 'i' );
            $break = new ezcWorkflowConditionVariable( 'i', new ezcWorkflowConditionIsEqual( 10 ) );
            $continue = new ezcWorkflowConditionVariable( 'i', new ezcWorkflowConditionIsLessThan( 10 ) );
        }
        else
        {
            $this->workflow = new ezcWorkflow( 'DecrementingLoop' );

            $start = 10;
            $step = new ezcWorkflowNodeVariableDecrement( 'i' );
            $break = new ezcWorkflowConditionVariable( 'i', new ezcWorkflowConditionIsEqual( 1 ) );
            $continue = new ezcWorkflowConditionVariable( 'i', new ezcWorkflowConditionIsGreaterThan( 1 ) );
        }

        $this->setUpReferences();

        $set = new ezcWorkflowNodeVariableSet(
          array( 'i' => $start )
        );

        $this->startNode->addOutNode( $set );

        $branch = new ezcWorkflowNodeExclusiveChoice;
        $branch->addInNode( $set );
        $branch->addInNode( $step );

        $branch->addConditionalOutNode( $continue, $step );
        $branch->addConditionalOutNode( $break, $this->endNode );
    }

    protected function setUpSetAddSubMulDiv()
    {
        $this->workflow = new ezcWorkflow( 'SetAddSubMulDiv' );
        $this->setUpReferences();

        $set = new ezcWorkflowNodeVariableSet(
          array( 'x' => 1 )
        );

        $add = new ezcWorkflowNodeVariableAdd(
          array( 'name' => 'x', 'value' => 1 )
        );

        $sub = new ezcWorkflowNodeVariableSub(
          array( 'name' => 'x', 'value' => 1 )
        );

        $mul = new ezcWorkflowNodeVariableMul(
          array( 'name' => 'x', 'value' => 2 )
        );

        $div = new ezcWorkflowNodeVariableDiv(
          array( 'name' => 'x', 'value' => 2 )
        );

        $this->startNode->addOutNode( $set );
        $set->addOutNode( $add );
        $add->addOutNode( $sub );
        $sub->addOutNode( $mul );
        $mul->addOutNode( $div );
        $this->endNode->addInNode( $div );
    }

    protected function setUpAddVariables()
    {
        $this->workflow = new ezcWorkflow( 'AddVariables' );
        $this->setUpReferences();

        $set = new ezcWorkflowNodeVariableSet(
          array( 'a' => 1, 'b' => 1 )
        );

        $add = new ezcWorkflowNodeVariableAdd(
          array( 'name' => 'b', 'value' => 'a' )
        );

        $this->startNode->addOutNode( $set );
        $set->addOutNode( $add );
        $this->endNode->addInNode( $add );
    }

    protected function setUpParallelSplitSynchronization()
    {
        $this->workflow = new ezcWorkflow( 'ParallelSplitSynchronization' );
        $this->setUpReferences();

        $this->branchNode = new ezcWorkflowNodeParallelSplit;

        $this->actionNodeA = new ezcWorkflowNodeAction( 'SOA' );
        $this->actionNodeB = new ezcWorkflowNodeAction( 'SOB' );
        $this->actionNodeC = new ezcWorkflowNodeAction( 'SOC' );

        $this->branchNode->addOutNode( $this->actionNodeA );
        $this->branchNode->addOutNode( $this->actionNodeB );
        $this->branchNode->addOutNode( $this->actionNodeC );

        $synchronization = new ezcWorkflowNodeSynchronization;

        $synchronization->addInNode( $this->actionNodeA );
        $synchronization->addInNode( $this->actionNodeB );
        $synchronization->addInNode( $this->actionNodeC );

        $this->startNode->addOutNode( $this->branchNode );
        $this->endNode->addInNode( $synchronization );
    }

    protected function setUpExclusiveChoiceSimpleMerge()
    {
        $this->workflow = new ezcWorkflow( 'ExclusiveChoiceSimpleMerge' );
        $this->setUpReferences();

        $this->branchNode = new ezcWorkflowNodeExclusiveChoice;

        $this->actionNodeA = new ezcWorkflowNodeAction( 'SOA' );
        $this->actionNodeB = new ezcWorkflowNodeAction( 'SOB' );

        $this->branchNode->addConditionalOutNode(
          new ezcWorkflowConditionVariable(
            'condition',
            new ezcWorkflowConditionIsTrue
          ),
          $this->actionNodeA
        );

        $this->branchNode->addConditionalOutNode(
          new ezcWorkflowConditionVariable(
            'condition',
            new ezcWorkflowConditionIsFalse
          ),
          $this->actionNodeB
        );

        $simpleMerge = new ezcWorkflowNodeSimpleMerge;

        $simpleMerge->addInNode( $this->actionNodeA );
        $simpleMerge->addInNode( $this->actionNodeB );

        $this->startNode->addOutNode( $this->branchNode );
        $this->endNode->addInNode( $simpleMerge );
    }

    protected function setUpMultiChoice( $mergeType )
    {
        $this->workflow = new ezcWorkflow( 'MultiChoice' . $mergeType );
        $this->setUpReferences();

        $set = new ezcWorkflowNodeVariableSet(
          array(
            'x' => 1, 'y' => 2
          )
        );

        $multiChoice        = new ezcWorkflowNodeMultiChoice;
        $this->actionNodeA  = new ezcWorkflowNodeAction( 'SOA' );
        $this->actionNodeB  = new ezcWorkflowNodeAction( 'SOB' );
        $this->actionNodeC  = new ezcWorkflowNodeAction( 'SOC' );

        $multiChoice->addConditionalOutNode(
          new ezcWorkflowConditionAnd(
            array(
              new ezcWorkflowConditionVariable(
                'x',
                new ezcWorkflowConditionIsEqual( 1 )
              ),
              new ezcWorkflowConditionNot(
                new ezcWorkflowConditionVariable(
                  'y',
                  new ezcWorkflowConditionIsEqual( 3 )
                )
              )
            )
          ),
          $this->actionNodeA
        );

        $multiChoice->addConditionalOutNode(
          new ezcWorkflowConditionOr(
            array(
              new ezcWorkflowConditionVariable(
                'x',
                new ezcWorkflowConditionIsEqual( 1 )
              ),
              new ezcWorkflowConditionVariable(
                'y',
                new ezcWorkflowConditionIsEqual( 2 )
              )
            )
          ),
          $this->actionNodeB
        );

        $multiChoice->addConditionalOutNode(
          new ezcWorkflowConditionXor(
            array(
              new ezcWorkflowConditionVariable(
                'x',
                new ezcWorkflowConditionIsEqual( 1 )
              ),
              new ezcWorkflowConditionVariable(
                'y',
                new ezcWorkflowConditionIsEqual( 1 )
              )
            )
          ),
          $this->actionNodeC
        );

        if ( $mergeType == 'SynchronizingMerge' )
        {
            $merge = new ezcWorkflowNodeSynchronizingMerge;
        }
        else
        {
            $merge = new ezcWorkflowNodeDiscriminator;
        }

        $merge->addInNode( $this->actionNodeA );
        $merge->addInNode( $this->actionNodeB );
        $merge->addInNode( $this->actionNodeC );

        $this->startNode->addOutNode( $set );
        $set->addOutNode( $multiChoice );
        $this->endNode->addInNode( $merge );
    }

    protected function setUpWorkflowWithSubWorkflow( $subWorkflow )
    {
        $this->workflow = new ezcWorkflow( 'WorkflowWithSubWorkflow' );
        $this->setUpReferences();

        $subWorkflow = new ezcWorkflowNodeSubWorkflow( $subWorkflow );

        $this->startNode->addOutNode( $subWorkflow );
        $this->endNode->addInNode( $subWorkflow );
    }

    protected function setUpReferences()
    {
        $this->startNode = $this->workflow->getStartNode();
        $this->endNode = $this->workflow->getEndNode();
    }
}
?>
