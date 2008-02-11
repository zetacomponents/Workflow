<?php
/**
 * @package Workflow
 * @subpackage Tests
 * @version //autogentag//
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once 'case.php';

/**
 * @package Workflow
 * @subpackage Tests
 */
class ezcWorkflowVisitorVisualizationTest extends ezcWorkflowTestCase
{
    protected $visitor;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( 'ezcWorkflowVisitorVisualizationTest' );
    }

    protected function setUp()
    {
        parent::setUp();

        $this->visitor = new ezcWorkflowVisitorVisualization;
    }

    public function testVisitStartEnd()
    {
        $this->setUpStartEnd();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'StartEnd' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitStartEnd2()
    {
        $this->visitor = new ezcWorkflowVisitorVisualization( array( 1 ) );

        $this->setUpStartEnd();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'StartEnd2' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitStartInputEnd()
    {
        $this->setUpStartInputEnd();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'StartInputEnd' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitStartSetEnd()
    {
        $this->setUpStartSetEnd();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'StartSetEnd' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitStartSetUnsetEnd()
    {
        $this->setUpStartSetUnsetEnd();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'StartSetUnsetEnd' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitIncrementingLoop()
    {
        $this->setUpLoop( 'increment' );
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'IncrementingLoop' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitDecrementingLoop()
    {
        $this->setUpLoop( 'decrement' );
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'DecrementingLoop' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitSetAddSubMulDiv()
    {
        $this->setUpSetAddSubMulDiv();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'SetAddSubMulDiv' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitAddVariables()
    {
        $this->setUpAddVariables();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'AddVariables' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitVariableEqualsVariable()
    {
        $this->setUpVariableEqualsVariable();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'VariableEqualsVariable' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitParallelSplitSynchronization()
    {
        $this->setUpParallelSplitSynchronization();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'ParallelSplitSynchronization' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitParallelSplitSynchronization2()
    {
        $this->setUpParallelSplitSynchronization2();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'ParallelSplitSynchronization2' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitExclusiveChoiceSimpleMerge()
    {
        $this->setUpExclusiveChoiceSimpleMerge();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'ExclusiveChoiceSimpleMerge' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitExclusiveChoiceWithUnconditionalOutNodeSimpleMerge()
    {
        $this->setUpExclusiveChoiceWithUnconditionalOutNodeSimpleMerge();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'ExclusiveChoiceWithUnconditionalOutNodeSimpleMerge' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitNestedExclusiveChoiceSimpleMerge()
    {
        $this->setUpNestedExclusiveChoiceSimpleMerge();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'NestedExclusiveChoiceSimpleMerge' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitMultiChoiceSynchronizingMerge()
    {
        $this->setUpMultiChoice( 'SynchronizingMerge' );
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'MultiChoiceSynchronizingMerge' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitMultiChoiceDiscriminator()
    {
        $this->setUpMultiChoice( 'Discriminator' );
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'MultiChoiceDiscriminator' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitWorkflowWithSubWorkflow()
    {
        $this->setUpWorkflowWithSubWorkflow( 'StartEnd' );
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'WorkflowWithSubWorkflowStartEnd' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitWorkflowWithCancelCaseSubWorkflow()
    {
        $this->setUpWorkflowWithSubWorkflow( 'ParallelSplitActionActionCancelCaseSynchronization' );
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'WorkflowWithSubWorkflowParallelSplitActionActionCancelCaseSynchronization' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitNestedLoops()
    {
        $this->setUpNestedLoops();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'NestedLoops' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitParallelSplitCancelCaseActionActionSynchronization()
    {
        $this->setUpCancelCase( 'first' );
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'ParallelSplitCancelCaseActionActionSynchronization' ),
          $this->visitor->__toString()
        );
    }

    public function testVisitParallelSplitActionActionCancelCaseSynchronization()
    {
        $this->setUpCancelCase( 'last' );
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'ParallelSplitActionActionCancelCaseSynchronization' ),
          $this->visitor->__toString()
        );
    }

    public function testProperties()
    {
        $this->assertTrue( isset( $this->visitor->colorHighlighted ) );
        $this->assertTrue( isset( $this->visitor->colorNormal ) );
        $this->assertFalse( isset( $this->visitor->foo ) );

        $this->assertEquals( '#204a87', $this->visitor->colorHighlighted );
        $this->assertEquals( '#2e3436', $this->visitor->colorNormal );

        $this->visitor->colorHighlighted   = '#2e3436';
        $this->visitor->colorNormal = '#204a87';

        $this->assertEquals( '#2e3436', $this->visitor->colorHighlighted );
        $this->assertEquals( '#204a87', $this->visitor->colorNormal );
    }

    public function testProperties2()
    {
        try
        {
            $foo = $this->visitor->foo;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testProperties3()
    {
        try
        {
            $this->visitor->foo = 'foo';
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testProperties4()
    {
        try
        {
            $this->visitor->colorHighlighted = null;
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }

        $this->fail();
    }

    protected function readExpected( $name )
    {
        return file_get_contents(
          dirname( __FILE__ ) . '/data/' . $name . '.dot'
        );
    }
}
?>
