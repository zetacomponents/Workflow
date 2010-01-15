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
class ezcWorkflowExecutionListenerTest extends ezcWorkflowTestCase
{
    protected $execution;
    protected $listener;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite(
          'ezcWorkflowExecutionListenerTest'
        );
    }

    protected function setUp()
    {
        parent::setUp();

        $this->execution = new ezcWorkflowTestExecution;
        $this->listener  = $this->getMock( 'ezcWorkflowExecutionListener' );
        $this->execution->addListener( $this->listener );
    }

    protected function tearDown()
    {
        $this->execution = NULL;
        $this->listener  = NULL;
    }

    public function testEventsForStartEnd()
    {
        $this->setUpExpectations( 'StartEnd' );
        $this->setUpStartEnd();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForStartEndVariableHandler()
    {
        $this->setUpExpectations( 'StartEndVariableHandler' );
        $this->setUpStartEndVariableHandler();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForStartInputEnd()
    {
        $this->setUpExpectations( 'StartInputEnd' );
        $this->setUpStartInputEnd();
        $this->execution->workflow = $this->workflow;
        $this->execution->setInputVariable( 'variable', 'value' );
        $this->execution->start();
    }

    public function testEventsForStartSetUnsetEnd()
    {
        $this->setUpExpectations( 'StartSetUnsetEnd' );
        $this->setUpStartSetUnsetEnd();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForIncrementingLoop()
    {
        $this->setUpExpectations( 'IncrementingLoop' );
        $this->setUpLoop( 'increment' );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForDecrementingLoop()
    {
        $this->setUpExpectations( 'DecrementingLoop' );
        $this->setUpLoop( 'decrement' );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForSetAddSubMulDiv()
    {
        $this->setUpExpectations( 'SetAddSubMulDiv' );
        $this->setUpSetAddSubMulDiv();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForAddVariables()
    {
        $this->setUpExpectations( 'AddVariables' );
        $this->setUpAddVariables();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForParallelSplitSynchronization()
    {
        $this->setUpExpectations( 'ParallelSplitSynchronization' );
        $this->setUpParallelSplitSynchronization();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForExclusiveChoiceSimpleMerge()
    {
        $this->setUpExpectations( 'ExclusiveChoiceSimpleMerge' );
        $this->setUpExclusiveChoiceSimpleMerge();
        $this->execution->workflow = $this->workflow;
        $this->execution->setVariables( array( 'condition' => true ) );
        $this->execution->start();
    }

    public function testEventsForExclusiveChoiceWithUnconditionalOutNodeSimpleMerge()
    {
        $this->setUpExpectations( 'ExclusiveChoiceWithUnconditionalOutNodeSimpleMerge' );
        $this->setUpExclusiveChoiceWithUnconditionalOutNodeSimpleMerge();
        $this->execution->workflow = $this->workflow;
        $this->execution->setVariables( array( 'condition' => false ) );
        $this->execution->start();
    }

    public function testEventsForNestedExclusiveChoiceSimpleMerge()
    {
        $this->setUpExpectations( 'NestedExclusiveChoiceSimpleMerge' );
        $this->setUpNestedExclusiveChoiceSimpleMerge();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForMultiChoiceSynchronizingMerge()
    {
        $this->setUpExpectations( 'MultiChoiceSynchronizingMerge' );
        $this->setUpMultiChoice( 'SynchronizingMerge' );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForMultiChoiceDiscriminator()
    {
        $this->setUpExpectations( 'MultiChoiceDiscriminator' );
        $this->setUpMultiChoice( 'Discriminator' );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForNestedLoops()
    {
        $this->setUpExpectations( 'NestedLoops' );
        $this->setUpNestedLoops();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForParallelSplitCancelCaseActionActionSynchronization()
    {
        $this->setUpExpectations( 'ParallelSplitCancelCaseActionActionSynchronization' );
        $this->setUpCancelCase( 'first' );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    public function testEventsForParallelSplitActionActionCancelCaseSynchronization()
    {
        $this->setUpExpectations( 'ParallelSplitActionActionCancelCaseSynchronization' );
        $this->setUpCancelCase( 'last' );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
    }

    protected function setUpExpectations( $log )
    {
        $lines = file(
          dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR .
          'WorkflowEventLogTiein' . DIRECTORY_SEPARATOR . 'tests' .
          DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $log . '.log'
        );

        $index = 0;

        foreach ( array_map('rtrim', $lines ) as $line )
        {
            $line = explode( ' ', $line );
            unset( $line[0], $line[1], $line[2], $line[3], $line[4], $line[5] );
            $line = join( ' ', $line );
            $line = preg_replace('/execution #(\d)+/', 'execution #0', $line);
            $line = preg_replace('/instance #(\d)+/', 'instance #0', $line);
            $line = preg_replace('/node #(\d)+/', 'node #0', $line);

            $this->listener
                 ->expects( $this->at( $index ) )
                 ->method( 'notify' )
                 ->with( $this->equalTo( $line ) );

            $index++;
        }
    }
}
?>
