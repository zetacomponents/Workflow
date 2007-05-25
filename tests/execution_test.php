<?php
/**
 * @package Workflow
 * @subpackage Tests
 * @version //autogentag//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once 'case.php';
require_once 'execution.php';

/**
 * @package Workflow
 * @subpackage Tests
 */
class ezcWorkflowExecutionTest extends ezcWorkflowTestCase
{
    protected $execution;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( 'ezcWorkflowExecutionTest' );
    }

    protected function setUp()
    {
        parent::setUp();
        $this->execution = new ezcWorkflowTestExecution;
        $this->execution->definitionHandler = $this->definition;
    }

    public function testInteractiveWorkflowRaisesException()
    {
        $this->setupEmptyWorkflow();

        $input = new ezcWorkflowNodeInput( array( 'choice' => new ezcWorkflowConditionIsBool ) );

        $this->startNode->addOutNode( $input );
        $this->endNode->addInNode( $input );

        try
        {
            $execution = new ezcWorkflowExecutionNonInteractive;
            $execution->setWorkflow( $this->workflow );
        }
        catch ( ezcWorkflowExecutionException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testExecuteStartEnd()
    {
        $this->setUpStartEnd();
        $this->execution->setWorkflow( $this->workflow );
        $this->execution->start();

        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteStartEndVariableHandler()
    {
        $this->setUpStartEndVariableHandler();
        $this->execution->setWorkflow( $this->workflow );
        $this->execution->start();

        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
        $this->assertEquals( 'bar', $this->execution->getVariable( 'foo' ) );
    }

    public function testExecuteStartInputEnd()
    {
        $this->setUpStartInputEnd();
        $this->execution->setWorkflow( $this->workflow );
        $this->execution->setInputVariable( 'variable', 'value' );
        $this->execution->start();

        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteStartSetUnsetEnd()
    {
        $this->setUpStartSetUnsetEnd();
        $this->execution->setWorkflow( $this->workflow );
        $this->execution->start();

        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteIncrementingLoop()
    {
        $this->setUpLoop( 'increment' );
        $this->execution->setWorkflow( $this->workflow );
        $this->execution->start();

        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteDecrementingLoop()
    {
        $this->setUpLoop( 'decrement' );
        $this->execution->setWorkflow( $this->workflow );
        $this->execution->start();

        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteSetAddSubMulDiv()
    {
        $this->setUpSetAddSubMulDiv();
        $this->execution->setWorkflow( $this->workflow );
        $this->execution->start();

        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
        $this->assertEquals( 1, $this->execution->getVariable( 'x' ) );
    }

    public function testExecuteAddVariables()
    {
        $this->setUpAddVariables();
        $this->execution->setWorkflow( $this->workflow );
        $this->execution->start();

        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
        $this->assertEquals( 2, $this->execution->getVariable( 'b' ) );
    }

    public function testExecuteParallelSplitSynchronization()
    {
        $this->setUpParallelSplitSynchronization();
        $this->execution->setWorkflow( $this->workflow );
        $this->execution->start();

        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteExclusiveChoiceSimpleMerge()
    {
        $this->setUpExclusiveChoiceSimpleMerge();
        $this->execution->setWorkflow( $this->workflow );
        $this->execution->setVariables( array( 'condition' => true ) );
        $this->execution->start();

        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteExclusiveChoiceSimpleMerge2()
    {
        $this->setUpExclusiveChoiceSimpleMerge();
        $this->execution->setWorkflow( $this->workflow );
        $this->execution->setVariables( array( 'condition' => false ) );
        $this->execution->start();

        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteMultiChoiceSynchronizingMerge()
    {
        $this->setUpMultiChoice( 'SynchronizingMerge' );
        $this->execution->setWorkflow( $this->workflow );
        $this->execution->start();

        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteMultiChoiceDiscriminator()
    {
        $this->setUpMultiChoice( 'Discriminator' );
        $this->execution->setWorkflow( $this->workflow );
        $this->execution->start();

        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testNonInteractiveSubWorkflow()
    {
        $this->setUpWorkflowWithSubWorkflow( 'StartEnd' );
        $this->workflow->definitionHandler = $this->definition;
        $this->execution->setWorkflow( $this->workflow );
        $this->execution->start();

        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testInteractiveSubWorkflow()
    {
        $this->setUpWorkflowWithSubWorkflow( 'StartInputEnd' );
        $this->workflow->definitionHandler = $this->definition;
        $this->execution->setWorkflow( $this->workflow );
        $this->execution->setInputVariableForSubWorkflow( 'variable', 'value' );
        $this->execution->start();

        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testListener()
    {
        $listener = $this->getMock( 'ezcWorkflowExecutionListener' );

        $this->assertTrue( $this->execution->addListener( $listener ) );
        $this->assertFalse( $this->execution->addListener( $listener ) );

        $this->assertTrue( $this->execution->removeListener( $listener ) );
        $this->assertFalse( $this->execution->removeListener( $listener ) );
    }
}
?>
