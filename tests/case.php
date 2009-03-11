<?php
/**
 * @package Workflow
 * @subpackage Tests
 * @version //autogentag//
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once 'service_object_that_does_not_finish.php';
require_once 'service_object_with_constructor.php';
require_once 'variable_handler.php';

/**
 * @package Workflow
 * @subpackage Tests
 */
abstract class ezcWorkflowTestCase extends ezcTestCase
{
    protected $xmlStorage;
    protected $workflow;
    protected $startNode;
    protected $endNode;
    protected $cancelNode;
    protected $branchNode;

    protected function setUp()
    {
        parent::setUp();

        $this->xmlStorage = new ezcWorkflowDefinitionStorageXml(
          dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR
        );

        if ( !class_exists( 'ServiceObject', false ) )
        {
            $this->getMock( 'ezcWorkflowServiceObject', array(), array(), 'ServiceObject' );
        }
    }

    protected function setUpEmptyWorkflow( $name = 'Empty' )
    {
        $this->workflow = new ezcWorkflow( $name );
    }

    protected function setUpStartEnd()
    {
        $this->workflow = new ezcWorkflow( 'StartEnd' );
        $this->workflow->startNode->addOutNode( $this->workflow->endNode );
    }

    protected function setUpStartEndVariableHandler()
    {
        $this->workflow = new ezcWorkflow( 'StartEndVariableHandler' );
        $this->workflow->startNode->addOutNode( $this->workflow->endNode );
        $this->workflow->addVariableHandler( 'foo', 'ezcWorkflowTestVariableHandler' );
    }

    protected function setUpStartInputEnd()
    {
        $this->workflow = new ezcWorkflow( 'StartInputEnd' );
        $inputNode = new ezcWorkflowNodeInput( array( 'variable' => new ezcWorkflowConditionIsString ) );

        $this->workflow->startNode->addOutNode( $inputNode );
        $this->workflow->endNode->addInNode( $inputNode );

        $this->workflow->addVariableHandler( 'foo', 'ezcWorkflowTestVariableHandler' );
    }

    protected function setUpStartInputEnd2()
    {
        $this->workflow = new ezcWorkflow( 'StartInputEnd2' );
        $inputNode = new ezcWorkflowNodeInput( array( 'variable' => new ezcWorkflowConditionInArray( array( '1', 2, 3 ) ) ) );

        $this->workflow->startNode->addOutNode( $inputNode );
        $this->workflow->endNode->addInNode( $inputNode );
    }

    protected function setUpStartSetEnd()
    {
        $this->workflow = new ezcWorkflow( 'StartSetEnd' );

        $set = new ezcWorkflowNodeVariableSet(
          array(
            'null' => null,
            'true' => true,
            'false' => false,
            'array' => array( 22, 4, 1978 ),
            'object' => new StdClass
          )
        );

        $this->workflow->startNode->addOutNode( $set );
        $set->addOutNode( $this->workflow->endNode );
    }

    protected function setUpStartSetUnsetEnd()
    {
        $this->workflow = new ezcWorkflow( 'StartSetUnsetEnd' );

        $set = new ezcWorkflowNodeVariableSet(
          array( 'x' => 1 )
        );

        $unset = new ezcWorkflowNodeVariableUnset( 'x' );

        $this->workflow->startNode->addOutNode( $set );
        $set->addOutNode( $unset );
        $unset->addOutNode( $this->workflow->endNode );
    }

    protected function setUpDecrementingLoop()
    {
        $this->setUpLoop( 'decrement' );
    }

    protected function setUpIncrementingLoop()
    {
        $this->setUpLoop( 'increment' );
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

        $set = new ezcWorkflowNodeVariableSet(
          array( 'i' => $start )
        );

        $this->workflow->startNode->addOutNode( $set );

        $loop = new ezcWorkflowNodeLoop;
        $loop->addInNode( $set )
             ->addInNode( $step )
             ->addConditionalOutNode( $continue, $step )
             ->addConditionalOutNode( $break, $this->workflow->endNode );
    }

    protected function setUpSetAddSubMulDiv()
    {
        $this->workflow = new ezcWorkflow( 'SetAddSubMulDiv' );

        $set = new ezcWorkflowNodeVariableSet(
          array( 'x' => 1 )
        );

        $add = new ezcWorkflowNodeVariableAdd(
          array( 'name' => 'x', 'operand' => 1 )
        );

        $sub = new ezcWorkflowNodeVariableSub(
          array( 'name' => 'x', 'operand' => 1 )
        );

        $mul = new ezcWorkflowNodeVariableMul(
          array( 'name' => 'x', 'operand' => 2 )
        );

        $div = new ezcWorkflowNodeVariableDiv(
          array( 'name' => 'x', 'operand' => 2 )
        );

        $this->workflow->startNode->addOutNode( $set );
        $set->addOutNode( $add );
        $add->addOutNode( $sub );
        $sub->addOutNode( $mul );
        $mul->addOutNode( $div );
        $this->workflow->endNode->addInNode( $div );
    }

    protected function setUpAddVariables()
    {
        $this->workflow = new ezcWorkflow( 'AddVariables' );

        $set = new ezcWorkflowNodeVariableSet(
          array( 'a' => 1, 'b' => 1 )
        );

        $add = new ezcWorkflowNodeVariableAdd(
          array( 'name' => 'b', 'operand' => 'a' )
        );

        $this->workflow->startNode->addOutNode( $set );
        $set->addOutNode( $add );
        $this->workflow->endNode->addInNode( $add );
    }

    protected function setUpAddVariables2()
    {
        $this->workflow = new ezcWorkflow( 'AddVariables2' );

        $set = new ezcWorkflowNodeVariableSet(
          array( 'a' => 'a', 'b' => 1 )
        );

        $add = new ezcWorkflowNodeVariableAdd(
          array( 'name' => 'b', 'operand' => 'a' )
        );

        $this->workflow->startNode->addOutNode( $set );
        $set->addOutNode( $add );
        $this->workflow->endNode->addInNode( $add );
    }

    protected function setUpAddVariables3()
    {
        $this->workflow = new ezcWorkflow( 'AddVariables3' );

        $set = new ezcWorkflowNodeVariableSet(
          array( 'a' => 1, 'b' => 'b' )
        );

        $add = new ezcWorkflowNodeVariableAdd(
          array( 'name' => 'b', 'operand' => 'a' )
        );

        $this->workflow->startNode->addOutNode( $set );
        $set->addOutNode( $add );
        $this->workflow->endNode->addInNode( $add );
    }

    protected function setUpVariableEqualsVariable()
    {
        $this->workflow = new ezcWorkflow( 'VariableEqualsVariable' );

        $set = new ezcWorkflowNodeVariableSet(
          array( 'a' => 1, 'b' => 1 )
        );

        $set2 = new ezcWorkflowNodeVariableSet(
          array( 'c' => 1 )
        );

        $set3 = new ezcWorkflowNodeVariableSet(
          array( 'c' => 0 )
        );

        $this->branchNode = new ezcWorkflowNodeExclusiveChoice;
        $this->branchNode->addInNode( $set );

        $this->branchNode->addConditionalOutNode(
          new ezcWorkflowConditionVariables(
            'a', 'b', new ezcWorkflowConditionIsEqual
          ),
          $set2
        );

        $this->branchNode->addConditionalOutNode(
          new ezcWorkflowConditionVariables(
            'a', 'b', new ezcWorkflowConditionIsNotEqual
          ),
          $set3
        );

        $simpleMerge = new ezcWorkflowNodeSimpleMerge;

        $simpleMerge->addInNode( $set2 )
                    ->addInNode( $set3 );

        $this->workflow->startNode->addOutNode( $set );
        $this->workflow->endNode->addInNode( $simpleMerge );
    }

    protected function setUpParallelSplitSynchronization()
    {
        $this->workflow   = new ezcWorkflow( 'ParallelSplitSynchronization' );
        $this->branchNode = new ezcWorkflowNodeParallelSplit;

        $actionNodeA = new ezcWorkflowNodeAction( 'ServiceObject' );
        $actionNodeB = new ezcWorkflowNodeAction( 'ServiceObject' );
        $actionNodeC = new ezcWorkflowNodeAction( 'ServiceObject' );

        $this->branchNode->addOutNode( $actionNodeA );
        $this->branchNode->addOutNode( $actionNodeB );
        $this->branchNode->addOutNode( $actionNodeC );

        $synchronization = new ezcWorkflowNodeSynchronization;

        $synchronization->addInNode( $actionNodeA );
        $synchronization->addInNode( $actionNodeB );
        $synchronization->addInNode( $actionNodeC );

        $this->workflow->startNode->addOutNode( $this->branchNode );
        $this->workflow->endNode->addInNode( $synchronization );
    }

    protected function setUpParallelSplitSynchronization2()
    {
        $this->workflow   = new ezcWorkflow( 'ParallelSplitSynchronization2' );
        $this->branchNode = new ezcWorkflowNodeParallelSplit;

        $foo = new ezcWorkflowNodeInput( array( 'foo' => new ezcWorkflowConditionIsString ) );
        $bar = new ezcWorkflowNodeInput( array( 'bar' => new ezcWorkflowConditionIsString ) );

        $this->branchNode->addOutNode( $foo );
        $this->branchNode->addOutNode( $bar );

        $synchronization = new ezcWorkflowNodeSynchronization;

        $synchronization->addInNode( $foo );
        $synchronization->addInNode( $bar );

        $this->workflow->startNode->addOutNode( $this->branchNode );
        $this->workflow->endNode->addInNode( $synchronization );
    }

    protected function setUpParallelSplitInvalidSynchronization()
    {
        $this->workflow = new ezcWorkflow( 'ParallelSplitInvalidSynchronization' );

        $branchA = new ezcWorkflowNodeParallelSplit;
        $branchB = new ezcWorkflowNodeParallelSplit;
        $branchC = new ezcWorkflowNodeParallelSplit;

        $branchA->addOutNode( $branchB )
                ->addOutNode( $branchC );

        $synchronization = new ezcWorkflowNodeSynchronization;

        $branchB->addOutNode( new ezcWorkflowNodeEnd )
                ->addOutNode( $synchronization );

        $branchC->addOutNode( $synchronization )
                ->addOutNode( new ezcWorkflowNodeEnd );

        $this->workflow->startNode->addOutNode( $branchA );
        $this->workflow->endNode->addInNode( $synchronization );
    }

    protected function setUpExclusiveChoiceSimpleMerge( $a = 'ezcWorkflowConditionIsTrue', $b = 'ezcWorkflowConditionIsFalse' )
    {
        $this->workflow   = new ezcWorkflow( 'ExclusiveChoiceSimpleMerge' );
        $this->branchNode = new ezcWorkflowNodeExclusiveChoice;

        $actionNodeA = new ezcWorkflowNodeAction( 'ServiceObject' );
        $actionNodeB = new ezcWorkflowNodeAction( 'ServiceObject' );

        $this->branchNode->addConditionalOutNode(
          new ezcWorkflowConditionVariable(
            'condition',
            new $a
          ),
          $actionNodeA
        );

        $this->branchNode->addConditionalOutNode(
          new ezcWorkflowConditionVariable(
            'condition',
            new $b
          ),
          $actionNodeB
        );

        $simpleMerge = new ezcWorkflowNodeSimpleMerge;

        $simpleMerge->addInNode( $actionNodeA );
        $simpleMerge->addInNode( $actionNodeB );

        $this->workflow->startNode->addOutNode( $this->branchNode );
        $this->workflow->endNode->addInNode( $simpleMerge );
    }

    protected function setUpExclusiveChoiceWithElseSimpleMerge()
    {
        $this->workflow   = new ezcWorkflow( 'ExclusiveChoiceWithElseSimpleMerge' );
        $this->branchNode = new ezcWorkflowNodeExclusiveChoice;

        $setX = new ezcWorkflowNodeVariableSet(
          array( 'x' => true )
        );

        $setY = new ezcWorkflowNodeVariableSet(
          array( 'y' => true )
        );

        $this->branchNode->addConditionalOutNode(
          new ezcWorkflowConditionVariable(
            'condition',
            new ezcWorkflowConditionIsTrue
          ),
          $setX,
          $setY
        );

        $simpleMerge = new ezcWorkflowNodeSimpleMerge;

        $simpleMerge->addInNode( $setX );
        $simpleMerge->addInNode( $setY );

        $this->workflow->startNode->addOutNode( $this->branchNode );
        $this->workflow->endNode->addInNode( $simpleMerge );
    }

    protected function setUpExclusiveChoiceWithUnconditionalOutNodeSimpleMerge()
    {
        $this->workflow = new ezcWorkflow( 'ExclusiveChoiceWithUnconditionalOutNodeSimpleMerge' );

        $setX = new ezcWorkflowNodeVariableSet(
          array( 'x' => true )
        );

        $setY = new ezcWorkflowNodeVariableSet(
          array( 'y' => true )
        );

        $setZ = new ezcWorkflowNodeVariableSet(
          array( 'z' => true )
        );

        $this->branchNode = new ezcWorkflowNodeExclusiveChoice;

        $this->branchNode->addConditionalOutNode(
          new ezcWorkflowConditionVariable(
            'condition',
            new ezcWorkflowConditionIsTrue
          ),
          $setX
        );

        $this->branchNode->addConditionalOutNode(
          new ezcWorkflowConditionVariable(
            'condition',
            new ezcWorkflowConditionIsFalse
          ),
          $setY
        );

        $this->branchNode->addOutNode( $setZ );

        $simpleMerge = new ezcWorkflowNodeSimpleMerge;

        $simpleMerge->addInNode( $setX )
                    ->addInNode( $setY )
                    ->addInNode( $setZ );

        $this->workflow->startNode->addOutNode( $this->branchNode );
        $this->workflow->endNode->addInNode( $simpleMerge );
    }

    protected function setUpNestedExclusiveChoiceSimpleMerge($x = true, $y = true)
    {
        $this->workflow = new ezcWorkflow( 'NestedExclusiveChoiceSimpleMerge' );

        $setX = new ezcWorkflowNodeVariableSet(
          array( 'x' => $x )
        );

        $setY = new ezcWorkflowNodeVariableSet(
          array( 'y' => $y )
        );

        $setZ1 = new ezcWorkflowNodeVariableSet(
          array( 'z' => true )
        );

        $setZ2 = new ezcWorkflowNodeVariableSet(
          array( 'z' => false )
        );

        $setZ3 = new ezcWorkflowNodeVariableSet(
          array( 'z' => false )
        );

        $this->workflow->startNode->addOutNode( $setX );

        $branch1 = new ezcWorkflowNodeExclusiveChoice;
        $branch1->addInNode( $setX );

        $branch1->addConditionalOutNode(
          new ezcWorkflowConditionVariable(
            'x',
            new ezcWorkflowConditionIsTrue
          ),
          $setY
        );

        $branch1->addConditionalOutNode(
          new ezcWorkflowConditionVariable(
            'x',
            new ezcWorkflowConditionIsFalse
          ),
          $setZ3
        );

        $branch2 = new ezcWorkflowNodeExclusiveChoice;
        $branch2->addInNode( $setY );

        $branch2->addConditionalOutNode(
          new ezcWorkflowConditionVariable(
            'y',
            new ezcWorkflowConditionIsTrue
          ),
          $setZ1
        );

        $branch2->addConditionalOutNode(
          new ezcWorkflowConditionVariable(
            'y',
            new ezcWorkflowConditionIsFalse
          ),
          $setZ2
        );

        $nestedMerge = new ezcWorkflowNodeSimpleMerge;
        $nestedMerge->addInNode( $setZ1 )
                    ->addInNode( $setZ2 );

        $merge = new ezcWorkflowNodeSimpleMerge;
        $merge->addInNode( $nestedMerge )
              ->addInNode( $setZ3 )
              ->addOutNode( $this->workflow->endNode );
    }

    protected function setUpMultiChoiceSynchronizingMerge()
    {
        $this->setUpMultiChoice( 'SynchronizingMerge' );
    }

    protected function setUpMultiChoiceDiscriminator()
    {
        $this->setUpMultiChoice( 'Discriminator' );
    }

    protected function setUpMultiChoice( $mergeType )
    {
        $this->workflow = new ezcWorkflow( 'MultiChoice' . $mergeType );

        $set = new ezcWorkflowNodeVariableSet(
          array(
            'x' => 1, 'y' => 2
          )
        );

        $multiChoice  = new ezcWorkflowNodeMultiChoice;
        $actionNodeA  = new ezcWorkflowNodeAction( 'ServiceObject' );
        $actionNodeB  = new ezcWorkflowNodeAction( 'ServiceObject' );
        $actionNodeC  = new ezcWorkflowNodeAction( 'ServiceObject' );

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
          $actionNodeA
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
          $actionNodeB
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
          $actionNodeC
        );

        if ( $mergeType == 'SynchronizingMerge' )
        {
            $merge = new ezcWorkflowNodeSynchronizingMerge;
        }
        else
        {
            $merge = new ezcWorkflowNodeDiscriminator;
        }

        $merge->addInNode( $actionNodeA );
        $merge->addInNode( $actionNodeB );
        $merge->addInNode( $actionNodeC );

        $this->workflow->startNode->addOutNode( $set );
        $set->addOutNode( $multiChoice );
        $this->workflow->endNode->addInNode( $merge );
    }

    protected function setUpWorkflowWithSubWorkflowStartEnd()
    {
        $this->setUpWorkflowWithSubWorkflow( 'StartEnd' );
    }

    protected function setUpWorkflowWithSubWorkflowParallelSplitActionActionCancelCaseSynchronization()
    {
        $this->setUpWorkflowWithSubWorkflow( 'ParallelSplitActionActionCancelCaseSynchronization' );
    }

    protected function setUpWorkflowWithSubWorkflow( $subWorkflow )
    {
        $this->workflow = new ezcWorkflow( 'WorkflowWithSubWorkflow' . $subWorkflow );
        $subWorkflow    = new ezcWorkflowNodeSubWorkflow( $subWorkflow );

        $this->workflow->startNode->addOutNode( $subWorkflow );
        $this->workflow->endNode->addInNode( $subWorkflow );
    }

    protected function setUpWorkflowWithSubWorkflowAndVariablePassing()
    {
        $this->workflow = new ezcWorkflow( 'WorkflowWithSubWorkflowAndVariablePassing' );
        $set            = new ezcWorkflowNodeVariableSet( array( 'x' => 1 ) );

        $subWorkflow = new ezcWorkflowNodeSubWorkflow(
          array(
            'workflow'  => 'IncrementVariable',
            'variables' => array(
              'in' => array(
                'x' => 'y'
              ),
              'out' => array(
                'y' => 'z'
              )
            )
          )
        );

        $subWorkflow->addInNode( $set );

        $this->workflow->startNode->addOutNode( $set );
        $this->workflow->endNode->addInNode( $subWorkflow );
    }

    protected function setUpNestedLoops()
    {
        $this->workflow = new ezcWorkflow( 'NestedLoops' );

        $innerSet      = new ezcWorkflowNodeVariableSet( array( 'j' => 1 ) );
        $innerStep     = new ezcWorkflowNodeVariableIncrement( 'j' );
        $innerBreak    = new ezcWorkflowConditionVariable( 'j', new ezcWorkflowConditionIsEqual( 2 ) );
        $innerContinue = new ezcWorkflowConditionVariable( 'j', new ezcWorkflowConditionIsLessThan( 2 ) );

        $innerLoop = new ezcWorkflowNodeLoop;
        $innerLoop->addInNode( $innerSet )
                  ->addInNode( $innerStep );

        $outerSet      = new ezcWorkflowNodeVariableSet( array( 'i' => 1 ) );
        $outerStep     = new ezcWorkflowNodeVariableIncrement( 'i' );
        $outerBreak    = new ezcWorkflowConditionVariable( 'i', new ezcWorkflowConditionIsEqual( 2 ) );
        $outerContinue = new ezcWorkflowConditionVariable( 'i', new ezcWorkflowConditionIsLessThan( 2 ) );

        $this->workflow->startNode->addOutNode( $outerSet );

        $outerLoop = new ezcWorkflowNodeLoop;
        $outerLoop->addInNode( $outerSet )
                  ->addInNode( $outerStep );

        $innerLoop->addConditionalOutNode( $innerContinue, $innerStep )
                  ->addConditionalOutNode( $innerBreak, $outerStep );

        $outerLoop->addConditionalOutNode( $outerContinue, $innerSet )
                  ->addConditionalOutNode( $outerBreak, $this->workflow->endNode );
    }

    protected function setUpParallelSplitCancelCaseActionActionSynchronization()
    {
        $this->setUpCancelCase( 'first' );
    }

    protected function setUpParallelSplitActionActionCancelCaseSynchronization()
    {
        $this->setUpCancelCase( 'last' );
    }

    protected function setUpCancelCase( $order )
    {
        if ( $order == 'first' )
        {
            $workflowName = 'ParallelSplitCancelCaseActionActionSynchronization';
        }
        else
        {
            $workflowName = 'ParallelSplitActionActionCancelCaseSynchronization';
        }

        $this->workflow = new ezcWorkflow( $workflowName );

        $this->branchNode = new ezcWorkflowNodeParallelSplit;
        $cancelNode       = new ezcWorkflowNodeCancel;
        $actionNodeA      = new ezcWorkflowNodeAction( 'ServiceObject' );
        $actionNodeB      = new ezcWorkflowNodeAction( 'ServiceObject' );
        $actionNodeC      = new ezcWorkflowNodeAction( 'ServiceObject' );
        $synchronization  = new ezcWorkflowNodeSynchronization;

        if ( $order == 'first' )
        {
            $this->branchNode->addOutNode( $cancelNode );
            $this->branchNode->addOutNode( $actionNodeB );
            $this->branchNode->addOutNode( $actionNodeC );

            $synchronization->addInNode( $cancelNode );
            $synchronization->addInNode( $actionNodeB );
            $synchronization->addInNode( $actionNodeC );
        }
        else
        {
            $this->branchNode->addOutNode( $actionNodeB );
            $this->branchNode->addOutNode( $actionNodeC );
            $this->branchNode->addOutNode( $cancelNode );

            $synchronization->addInNode( $actionNodeB );
            $synchronization->addInNode( $actionNodeC );
            $synchronization->addInNode( $cancelNode );
        }

        $this->workflow->startNode->addOutNode( $actionNodeA );
        $actionNodeA->addOutNode( $this->branchNode );
        $this->workflow->endNode->addInNode( $synchronization );
    }

    protected function setUpWorkflowWithFinalActivitiesAfterCancellation()
    {
        $this->workflow = new ezcWorkflow( 'WorkflowWithFinalActivitiesAfterCancellation' );
        $cancelNode     = new ezcWorkflowNodeCancel;

        $this->workflow->startNode->addOutNode( $cancelNode );
        $this->workflow->endNode->addInNode( $cancelNode );

        $set = new ezcWorkflowNodeVariableSet(
          array( 'finalActivityExecuted' => true )
        );

        $set->addOutNode( new ezcWorkflowNodeEnd );

        $this->workflow->finallyNode->addOutNode( $set );
    }

    protected function setUpServiceObjectWithArguments()
    {
        $this->setUpEmptyWorkflow( 'ServiceObjectWithArguments' );

        $action = new ezcWorkflowNodeAction(
          array(
            'class' => 'ServiceObjectWithConstructor',
            'arguments' => array(
              array( 'Sebastian' ), 22, 'April', 19.78, null, new StdClass
            )
          )
        );

        $this->workflow->startNode->addOutNode( $action );
        $this->workflow->endNode->addInNode( $action );
    }

    protected function setUpApprovalProcess()
    {
        $this->workflow = new ezcWorkflow( 'ApprovalProcess' );

        $init = new ezcWorkflowNodeVariableSet(
          array( 'approved_by_a' => false, 'approved_by_b' => false )
        );

        $approveA = new ezcWorkflowNodeVariableSet(
          array( 'approved_by_a' => true )
        );

        $approvedByA = new ezcWorkflowConditionVariable(
          'approved_by_a', new ezcWorkflowConditionIsTrue
        );

        $notApprovedByA = new ezcWorkflowConditionVariable(
          'approved_by_a', new ezcWorkflowConditionIsFalse
        );

        $approveB = new ezcWorkflowNodeVariableSet(
          array( 'approved_by_b' => true )
        );

        $approvedByB = new ezcWorkflowConditionVariable(
          'approved_by_b', new ezcWorkflowConditionIsTrue
        );

        $notApprovedByB = new ezcWorkflowConditionVariable(
          'approved_by_b', new ezcWorkflowConditionIsFalse
        );

        $loop = new ezcWorkflowNodeLoop;
        $loop->addInNode( $init )
             ->addInNode( $approveA )
             ->addInNode( $approveB )
             ->addConditionalOutNode( $notApprovedByA, $approveA )
             ->addConditionalOutNode( $notApprovedByB, $approveB )
             ->addConditionalOutNode( new ezcWorkflowConditionAnd( array( $approvedByA, $approvedByB ) ), $this->workflow->endNode );

        $this->workflow->startNode->addOutNode( $init );
    }

    public static function workflowNameProvider()
    {
        return array(
          array( 'AddVariables', 4 ),
          array( 'ApprovalProcess', 6 ),
          array( 'DecrementingLoop', 5 ),
          array( 'ExclusiveChoiceSimpleMerge', 6 ),
          array( 'ExclusiveChoiceWithElseSimpleMerge', 6 ),
          array( 'ExclusiveChoiceWithUnconditionalOutNodeSimpleMerge', 7 ),
          array( 'IncrementingLoop', 5 ),
          array( 'MultiChoiceDiscriminator', 8 ),
          array( 'MultiChoiceSynchronizingMerge', 8 ),
          array( 'NestedExclusiveChoiceSimpleMerge', 11 ),
          array( 'NestedLoops', 8 ),
          array( 'ParallelSplitSynchronization', 7 ),
          array( 'ParallelSplitSynchronization2', 6 ),
          array( 'ParallelSplitActionActionCancelCaseSynchronization', 8 ),
          array( 'ParallelSplitCancelCaseActionActionSynchronization', 8 ),
          array( 'ServiceObjectWithArguments', 3 ),
          array( 'SetAddSubMulDiv', 7 ),
          array( 'StartEnd', 2 ),
          array( 'StartInputEnd', 3 ),
          array( 'StartInputEnd2', 3 ),
          array( 'StartEndVariableHandler', 2 ),
          array( 'StartSetEnd', 3 ),
          array( 'StartSetUnsetEnd', 4 ),
          array( 'VariableEqualsVariable', 7 ),
          array( 'WorkflowWithFinalActivitiesAfterCancellation', 3 ),
          array( 'WorkflowWithSubWorkflowStartEnd', 3 ),
          array( 'WorkflowWithSubWorkflowAndVariablePassing', 4 ),
          array( 'WorkflowWithSubWorkflowParallelSplitActionActionCancelCaseSynchronization', 3 )
        );
    }
}
?>
