<?php
/**
 * @package Workflow
 * @subpackage Tests
 * @version //autogentag//
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once 'case.php';
require_once 'execution.php';

/**
 * @package Workflow
 * @subpackage Tests
 */
class ezcWorkflowExecutionPluginTest extends ezcWorkflowTestCase
{
    protected $execution;
    protected $plugin;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite(
          'ezcWorkflowExecutionPluginTest'
        );
    }

    protected function setUp()
    {
        parent::setUp();

        $this->execution = new ezcWorkflowTestExecution;
        $this->plugin    = $this->getMock( 'ezcWorkflowExecutionPlugin' );
        $this->execution->addPlugin( $this->plugin );
    }

    protected function tearDown()
    {
        $this->execution = NULL;
        $this->plugin    = NULL;
    }

    public function testEventsForStartEnd()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpStartEnd();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForStartEndVariableHandler()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpStartEndVariableHandler();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForStartInputEnd()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpStartInputEnd();
        $this->execution->workflow = $this->workflow;
        $this->execution->setInputVariable( 'variable', 'value' );
        $this->execution->start();
    }

    public function testEventsForStartSetUnsetEnd()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 4 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 4 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 4 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterVariableUnset' );

        $this->setUpStartSetUnsetEnd();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForIncrementingLoop()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 22 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 22 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 22 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 10 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 10 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpLoop( 'increment' );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForDecrementingLoop()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 22 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 22 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 22 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 10 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 10 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpLoop( 'decrement' );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForSetAddSubMulDiv()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 7 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 7 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 7 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 5 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 5 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpSetAddSubMulDiv();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForAddVariables()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 4 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 4 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 4 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpAddVariables();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForVariableEqualsVariable()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 6 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 6 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 6 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpVariableEqualsVariable();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForParallelSplitSynchronization()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 7 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 7 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 7 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 4 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 4 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpParallelSplitSynchronization();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForParallelSplitSynchronization2()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 6 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 6 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 6 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpParallelSplitSynchronization2();
        $this->execution->workflow = $this->workflow;
        $this->execution->setVariables( array( 'foo' => 'bar', 'bar' => 'foo' ) );
        $this->execution->start();
    }

    public function testEventsForExclusiveChoiceSimpleMerge()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 5 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 5 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 5 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpExclusiveChoiceSimpleMerge();
        $this->execution->workflow = $this->workflow;
        $this->execution->setVariables( array( 'condition' => true ) );
        $this->execution->start();
    }

    public function testEventsForExclusiveChoiceWithElseSimpleMerge()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 5 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 5 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 5 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpExclusiveChoiceWithElseSimpleMerge();
        $this->execution->workflow = $this->workflow;
        $this->execution->setVariables( array( 'condition' => true ) );
        $this->execution->start();
    }

    public function testEventsForExclusiveChoiceWithUnconditionalOutNodeSimpleMerge()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 6 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 6 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 6 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpExclusiveChoiceWithUnconditionalOutNodeSimpleMerge();
        $this->execution->workflow = $this->workflow;
        $this->execution->setVariables( array( 'condition' => false ) );
        $this->execution->start();
    }

    public function testEventsForNestedExclusiveChoiceSimpleMerge()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 9 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 9 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 9 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpNestedExclusiveChoiceSimpleMerge();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForMultiChoiceSynchronizingMerge()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 8 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 8 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 8 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 4 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 4 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpMultiChoice( 'SynchronizingMerge' );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForMultiChoiceDiscriminator()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 8 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 8 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 8 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 4 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 4 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpMultiChoice( 'Discriminator' );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForNonInteractiveSubWorkflow()
    {
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 5 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 5 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 5 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpWorkflowWithSubWorkflow( 'StartEnd' );
        $this->execution->definitionStorage = $this->xmlStorage;
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForInteractiveSubWorkflow()
    {
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 6 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 6 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 6 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpWorkflowWithSubWorkflow( 'StartInputEnd' );
        $this->execution->definitionStorage = $this->xmlStorage;
        $this->execution->workflow = $this->workflow;
        $this->execution->setInputVariableForSubWorkflow( 'variable', 'value' );
        $this->execution->start();
    }

    public function testEventsForWorkflowWithCancelCaseSubWorkflow()
    {
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 7 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 7 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 5 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 5 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpWorkflowWithSubWorkflow( 'ParallelSplitActionActionCancelCaseSynchronization' );
        $this->execution->definitionStorage = $this->xmlStorage;
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForNestedLoops()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 10 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 10 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 10 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 4 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 4 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpNestedLoops();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForWorkflowWithSubWorkflowAndVariablePassing()
    {
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 7 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 7 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 7 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 4 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 4 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpWorkflowWithSubWorkflowAndVariablePassing();
        $this->execution->definitionStorage = $this->xmlStorage;
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForParallelSplitCancelCaseActionActionSynchronization()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 2 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpCancelCase( 'first' );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForParallelSplitActionActionCancelCaseSynchronization()
    {
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionSuspended' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionResumed' );
        $this->plugin->expects( $this->exactly( 1 ) )->method( 'afterExecutionCancelled' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterExecutionEnded' );
        $this->plugin->expects( $this->exactly( 5 ) )->method( 'beforeNodeActivated' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 5 ) )->method( 'afterNodeActivated' );
        $this->plugin->expects( $this->exactly( 3 ) )->method( 'afterNodeExecuted' );
        $this->plugin->expects( $this->exactly( 4 ) )->method( 'afterThreadStarted' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterThreadEnded' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableSet' )->will( $this->returnArgument( 2 ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableSet' );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'beforeVariableUnset' )->will( $this->returnValue( true ) );
        $this->plugin->expects( $this->exactly( 0 ) )->method( 'afterVariableUnset' );

        $this->setUpCancelCase( 'last' );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }
}
?>
