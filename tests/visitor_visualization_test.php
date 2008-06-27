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
          (string)$this->visitor
        );
    }

    public function testVisitStartEnd2()
    {
        $this->visitor->options['highlightedNodes'] = array( 1 );

        $this->setUpStartEnd();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'StartEnd2' ),
          (string)$this->visitor
        );
    }

    public function testVisitStartInputEnd()
    {
        $this->setUpStartInputEnd();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'StartInputEnd' ),
          (string)$this->visitor
        );
    }

    public function testVisitStartSetEnd()
    {
        $this->setUpStartSetEnd();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'StartSetEnd' ),
          (string)$this->visitor
        );
    }

    public function testVisitStartSetUnsetEnd()
    {
        $this->setUpStartSetUnsetEnd();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'StartSetUnsetEnd' ),
          (string)$this->visitor
        );
    }

    public function testVisitIncrementingLoop()
    {
        $this->setUpLoop( 'increment' );
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'IncrementingLoop' ),
          (string)$this->visitor
        );
    }

    public function testVisitDecrementingLoop()
    {
        $this->setUpLoop( 'decrement' );
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'DecrementingLoop' ),
          (string)$this->visitor
        );
    }

    public function testVisitSetAddSubMulDiv()
    {
        $this->setUpSetAddSubMulDiv();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'SetAddSubMulDiv' ),
          (string)$this->visitor
        );
    }

    public function testVisitAddVariables()
    {
        $this->setUpAddVariables();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'AddVariables' ),
          (string)$this->visitor
        );
    }

    public function testVisitVariableEqualsVariable()
    {
        $this->setUpVariableEqualsVariable();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'VariableEqualsVariable' ),
          (string)$this->visitor
        );
    }

    public function testVisitParallelSplitSynchronization()
    {
        $this->setUpParallelSplitSynchronization();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'ParallelSplitSynchronization' ),
          (string)$this->visitor
        );
    }

    public function testVisitParallelSplitSynchronization2()
    {
        $this->setUpParallelSplitSynchronization2();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'ParallelSplitSynchronization2' ),
          (string)$this->visitor
        );
    }

    public function testVisitExclusiveChoiceSimpleMerge()
    {
        $this->setUpExclusiveChoiceSimpleMerge();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'ExclusiveChoiceSimpleMerge' ),
          (string)$this->visitor
        );
    }

    public function testVisitExclusiveChoiceWithUnconditionalOutNodeSimpleMerge()
    {
        $this->setUpExclusiveChoiceWithUnconditionalOutNodeSimpleMerge();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'ExclusiveChoiceWithUnconditionalOutNodeSimpleMerge' ),
          (string)$this->visitor
        );
    }

    public function testVisitNestedExclusiveChoiceSimpleMerge()
    {
        $this->setUpNestedExclusiveChoiceSimpleMerge();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'NestedExclusiveChoiceSimpleMerge' ),
          (string)$this->visitor
        );
    }

    public function testVisitMultiChoiceSynchronizingMerge()
    {
        $this->setUpMultiChoice( 'SynchronizingMerge' );
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'MultiChoiceSynchronizingMerge' ),
          (string)$this->visitor
        );
    }

    public function testVisitMultiChoiceDiscriminator()
    {
        $this->setUpMultiChoice( 'Discriminator' );
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'MultiChoiceDiscriminator' ),
          (string)$this->visitor
        );
    }

    public function testVisitWorkflowWithSubWorkflow()
    {
        $this->setUpWorkflowWithSubWorkflow( 'StartEnd' );
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'WorkflowWithSubWorkflowStartEnd' ),
          (string)$this->visitor
        );
    }

    public function testVisitWorkflowWithCancelCaseSubWorkflow()
    {
        $this->setUpWorkflowWithSubWorkflow( 'ParallelSplitActionActionCancelCaseSynchronization' );
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'WorkflowWithSubWorkflowParallelSplitActionActionCancelCaseSynchronization' ),
          (string)$this->visitor
        );
    }

    public function testVisitNestedLoops()
    {
        $this->setUpNestedLoops();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'NestedLoops' ),
          (string)$this->visitor
        );
    }

    public function testVisitParallelSplitCancelCaseActionActionSynchronization()
    {
        $this->setUpCancelCase( 'first' );
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'ParallelSplitCancelCaseActionActionSynchronization' ),
          (string)$this->visitor
        );
    }

    public function testVisitParallelSplitActionActionCancelCaseSynchronization()
    {
        $this->setUpCancelCase( 'last' );
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'ParallelSplitActionActionCancelCaseSynchronization' ),
          (string)$this->visitor
        );
    }

    public function testProperties()
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

    public function testProperties2()
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

    public function testOptions()
    {
        $this->assertTrue( isset( $this->visitor->options['colorHighlighted'] ) );
        $this->assertTrue( isset( $this->visitor->options['colorNormal'] ) );
        $this->assertFalse( isset( $this->visitor->foo ) );

        $this->assertEquals( '#cc0000', $this->visitor->options['colorHighlighted'] );
        $this->assertEquals( '#2e3436', $this->visitor->options['colorNormal'] );

        $this->visitor->options['colorHighlighted'] = '#2e3436';
        $this->visitor->options['colorNormal'] = '#cc0000';

        $this->assertEquals( '#2e3436', $this->visitor->options['colorHighlighted'] );
        $this->assertEquals( '#cc0000', $this->visitor->options['colorNormal'] );
    }

    public function testOptions2()
    {
        try
        {
            $this->visitor->options['colorHighlighted'] = null;
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testOptions3()
    {
        try
        {
            $this->visitor->options = null;
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testOptions4()
    {
        try
        {
            $this->visitor->options['highlightedNodes'] = null;
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testOptions5()
    {
        try
        {
            $this->visitor->options['foo'] = null;
        }
        catch ( ezcBasePropertyNotFoundException $e )
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
