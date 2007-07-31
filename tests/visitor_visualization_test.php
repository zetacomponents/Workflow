<?php
/**
 * @package Workflow
 * @subpackage Tests
 * @version //autogentag//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
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

    public function testVisitParallelSplitSynchronization()
    {
        $this->setUpParallelSplitSynchronization();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'ParallelSplitSynchronization' ),
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
          $this->readExpected( 'WorkflowWithSubWorkflow' ),
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

    protected function readExpected( $name )
    {
        return file_get_contents(
          dirname( __FILE__ ) . '/data/' . $name . '.dot'
        );
    }
}
?>
