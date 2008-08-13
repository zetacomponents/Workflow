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

    /**
     * @dataProvider workflowNameProvider
     */
    public function testVisualizeWorkflow($workflowName)
    {
        $setupMethod = 'setUp' . $workflowName;

        $this->$setupMethod();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( $workflowName ),
          (string)$this->visitor
        );
    }

    public function testHighlightedStartNode()
    {
        $this->visitor->options['highlightedNodes'] = array( 1 );

        $this->setUpStartEnd();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'StartEnd2' ),
          (string)$this->visitor
        );
    }

    /**
     * @expectedException ezcBasePropertyNotFoundException
     */
    public function testProperties()
    {
        $foo = $this->visitor->foo;
    }

    /**
     * @expectedException ezcBasePropertyNotFoundException
     */
    public function testProperties2()
    {
        $this->visitor->foo = 'foo';
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

    /**
     * @expectedException ezcBaseValueException
     */
    public function testOptions2()
    {
        $this->visitor->options['colorHighlighted'] = null;
    }

    /**
     * @expectedException ezcBaseValueException
     */
    public function testOptions3()
    {
        $this->visitor->options = null;
    }

    /**
     * @expectedException ezcBaseValueException
     */
    public function testOptions4()
    {
        $this->visitor->options['highlightedNodes'] = null;
    }

    /**
     * @expectedException ezcBasePropertyNotFoundException
     */
    public function testOptions5()
    {
        $this->visitor->options['foo'] = null;
    }

    protected function readExpected( $name )
    {
        return file_get_contents(
          dirname( __FILE__ ) . '/data/' . $name . '.dot'
        );
    }
}
?>
