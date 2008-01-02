<?php
/**
 * @package Workflow
 * @subpackage Tests
 * @version //autogentag//
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
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
    protected $definition;
    protected $workflow;
    protected $startNode;
    protected $endNode;
    protected $branchNode;

    protected function setUp()
    {
        parent::setUp();

        $this->definition = new ezcWorkflowDefinitionStorageXml(
          dirname( __FILE__ ) . '/data/'
        );

        if ( !class_exists( 'ServiceObject', false ) )
        {
            $this->getMock( 'ezcWorkflowServiceObject', array(), array(), 'ServiceObject' );
        }
    }

    protected function setUpEmptyWorkflow( $name = 'Empty' )
    {
        $this->workflow = new ezcWorkflow( $name );
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

        $inputNode = new ezcWorkflowNodeInput( array( 'variable' => new ezcWorkflowConditionIsString ) );

        $this->startNode->addOutNode( $inputNode );
        $this->endNode->addInNode( $inputNode );

        $this->workflow->addVariableHandler( 'foo', 'ezcWorkflowTestVariableHandler' );
    }

    protected function setUpStartSetEnd()
    {
        $this->workflow = new ezcWorkflow( 'StartSetEnd' );
        $this->setUpReferences();

        $set = new ezcWorkflowNodeVariableSet(
          array(
            'null' => null,
            'true' => true,
            'false' => false,
            'array' => array( 22, 4, 1978 ),
            'object' => new StdClass
          )
        );

        $this->startNode->addOutNode( $set );
        $set->addOutNode( $this->endNode );
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

        $loop = new ezcWorkflowNodeLoop;
        $loop->addInNode( $set )
             ->addInNode( $step )
             ->addConditionalOutNode( $continue, $step )
             ->addConditionalOutNode( $break, $this->endNode );
    }

    protected function setUpSetAddSubMulDiv()
    {
        $this->workflow = new ezcWorkflow( 'SetAddSubMulDiv' );
        $this->setUpReferences();

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
          array( 'name' => 'b', 'operand' => 'a' )
        );

        $this->startNode->addOutNode( $set );
        $set->addOutNode( $add );
        $this->endNode->addInNode( $add );
    }

    protected function setUpAddVariables2()
    {
        $this->workflow = new ezcWorkflow( 'AddVariables2' );
        $this->setUpReferences();

        $set = new ezcWorkflowNodeVariableSet(
          array( 'a' => 'a', 'b' => 1 )
        );

        $add = new ezcWorkflowNodeVariableAdd(
          array( 'name' => 'b', 'operand' => 'a' )
        );

        $this->startNode->addOutNode( $set );
        $set->addOutNode( $add );
        $this->endNode->addInNode( $add );
    }

    protected function setUpAddVariables3()
    {
        $this->workflow = new ezcWorkflow( 'AddVariables3' );
        $this->setUpReferences();

        $set = new ezcWorkflowNodeVariableSet(
          array( 'a' => 1, 'b' => 'b' )
        );

        $add = new ezcWorkflowNodeVariableAdd(
          array( 'name' => 'b', 'operand' => 'a' )
        );

        $this->startNode->addOutNode( $set );
        $set->addOutNode( $add );
        $this->endNode->addInNode( $add );
    }

    protected function setUpVariableEqualsVariable()
    {
        $this->workflow = new ezcWorkflow( 'VariableEqualsVariable' );
        $this->setUpReferences();

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

        $this->startNode->addOutNode( $set );
        $this->endNode->addInNode( $simpleMerge );
    }

    protected function setUpParallelSplitSynchronization()
    {
        $this->workflow = new ezcWorkflow( 'ParallelSplitSynchronization' );
        $this->setUpReferences();

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

        $this->startNode->addOutNode( $this->branchNode );
        $this->endNode->addInNode( $synchronization );
    }

    protected function setUpParallelSplitSynchronization2()
    {
        $this->workflow = new ezcWorkflow( 'ParallelSplitSynchronization2' );
        $this->setUpReferences();

        $this->branchNode = new ezcWorkflowNodeParallelSplit;

        $foo = new ezcWorkflowNodeInput( array( 'foo' => new ezcWorkflowConditionIsString ) );
        $bar = new ezcWorkflowNodeInput( array( 'bar' => new ezcWorkflowConditionIsString ) );

        $this->branchNode->addOutNode( $foo );
        $this->branchNode->addOutNode( $bar );

        $synchronization = new ezcWorkflowNodeSynchronization;

        $synchronization->addInNode( $foo );
        $synchronization->addInNode( $bar );

        $this->startNode->addOutNode( $this->branchNode );
        $this->endNode->addInNode( $synchronization );
    }

    protected function setUpParallelSplitInvalidSynchronization()
    {
        $this->workflow = new ezcWorkflow( 'ParallelSplitInvalidSynchronization' );
        $this->setUpReferences();

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

        $this->startNode->addOutNode( $branchA );
        $this->endNode->addInNode( $synchronization );
    }

    protected function setUpExclusiveChoiceSimpleMerge( $a = 'ezcWorkflowConditionIsTrue', $b = 'ezcWorkflowConditionIsFalse' )
    {
        $this->workflow = new ezcWorkflow( 'ExclusiveChoiceSimpleMerge' );
        $this->setUpReferences();

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

        $this->startNode->addOutNode( $this->branchNode );
        $this->endNode->addInNode( $simpleMerge );
    }

    protected function setUpExclusiveChoiceWithElseSimpleMerge()
    {
        $this->workflow = new ezcWorkflow( 'ExclusiveChoiceWithElseSimpleMerge' );
        $this->setUpReferences();

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

        $this->startNode->addOutNode( $this->branchNode );
        $this->endNode->addInNode( $simpleMerge );
    }

    protected function setUpExclusiveChoiceWithUnconditionalOutNodeSimpleMerge()
    {
        $this->workflow = new ezcWorkflow( 'ExclusiveChoiceWithUnconditionalOutNodeSimpleMerge' );
        $this->setUpReferences();

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

        $this->startNode->addOutNode( $this->branchNode );
        $this->endNode->addInNode( $simpleMerge );
    }

    protected function setUpNestedExclusiveChoiceSimpleMerge($x = true, $y = true)
    {
        $this->workflow = new ezcWorkflow( 'NestedExclusiveChoiceSimpleMerge' );
        $this->setUpReferences();

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

        $this->startNode->addOutNode( $setX );

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
              ->addOutNode( $this->endNode );
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

    protected function setUpWorkflowWithSubWorkflowAndVariablePassing()
    {
        $this->workflow = new ezcWorkflow( 'WorkflowWithSubWorkflowAndVariablePassing' );
        $this->setUpReferences();

        $set = new ezcWorkflowNodeVariableSet( array( 'x' => 1 ) );

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

        $this->startNode->addOutNode( $set );
        $this->endNode->addInNode( $subWorkflow );
    }

    protected function setUpNestedLoops()
    {
        $this->workflow = new ezcWorkflow( 'NestedLoops' );
        $this->setUpReferences();

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

        $this->startNode->addOutNode( $outerSet );

        $outerLoop = new ezcWorkflowNodeLoop;
        $outerLoop->addInNode( $outerSet )
                  ->addInNode( $outerStep );

        $innerLoop->addConditionalOutNode( $innerContinue, $innerStep )
                  ->addConditionalOutNode( $innerBreak, $outerStep );

        $outerLoop->addConditionalOutNode( $outerContinue, $innerSet )
                  ->addConditionalOutNode( $outerBreak, $this->endNode );
    }

    protected function setUpReferences()
    {
        $this->startNode = $this->workflow->startNode;
        $this->endNode = $this->workflow->endNode;
    }
}
?>
