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
class ezcWorkflowTest extends ezcWorkflowTestCase
{
    protected $workflow;
    protected $startNode;
    protected $endNode;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( 'ezcWorkflowTest' );
    }

    public function testGetName()
    {
        $this->setUpStartEnd();
        $this->assertEquals( 'StartEnd', $this->workflow->name );
    }

    public function testGetSetId()
    {
        $this->setUpStartEnd();
        $this->assertFalse( $this->workflow->id );

        $this->workflow->id = 1;

        $this->assertEquals( 1, $this->workflow->id );
    }

    public function testGetSetDefinition()
    {
        $this->setUpStartEnd();
        $this->assertNull( $this->workflow->definitionStorage );

        $this->workflow->definitionStorage = $this->definition;
        $this->assertNotNull( $this->workflow->definitionStorage );
    }

    /**
     * @expectedException ezcBaseValueException
     */
    public function testGetSetName()
    {
        $workflow = new ezcWorkflow( 'Test' );
        $this->assertEquals( 'Test', $workflow->name );

        $workflow->name = 'Test2';
        $this->assertEquals( 'Test2', $workflow->name );

        $workflow->name = array();
    }

    public function testGetNodes()
    {
        $this->setUpStartEnd();
        $nodes = $this->workflow->nodes;

        $this->assertSame( $this->workflow->startNode, $nodes[1] );
        $this->assertSame( $this->workflow->endNode, $nodes[2] );
    }

    public function testHasSubWorkflows()
    {
        $this->setUpStartEnd();
        $this->assertFalse( $this->workflow->hasSubWorkflows() );

        $this->setUpWorkflowWithSubWorkflow( 'StartEnd' );
        $this->assertTrue( $this->workflow->hasSubWorkflows() );
    }

    public function testIsInteractive()
    {
        $this->setUpStartEnd();
        $this->assertFalse( $this->workflow->isInteractive() );
    }

    public function testVerify()
    {
        $this->setUpStartEnd();
        $this->workflow->verify();
    }

    /**
     * @expectedException ezcWorkflowDefinitionStorageException
     */
    public function testVerify2()
    {
        $workflow = new ezcWorkflow( 'Test' );
        $workflow->verify();
    }

    /**
     * @expectedException ezcWorkflowDefinitionStorageException
     */
    public function testVerify3()
    {
        $workflow = new ezcWorkflow( 'Test' );
        $workflow->startNode->addOutNode( new ezcWorkflowNodeStart );
        $workflow->verify();
    }

    /**
     * @expectedException ezcWorkflowDefinitionStorageException
     */
    public function testVerify4()
    {
        $workflow = new ezcWorkflow( 'Test' );
        $workflow->finallyNode->addOutNode( new ezcWorkflowNodeFinally );
        $workflow->verify();
    }

    public function testVariableHandler()
    {
        $this->setUpStartEnd();

        $this->assertFalse( $this->workflow->removeVariableHandler( 'foo' ) );

        $this->workflow->setVariableHandlers(
          array( 'foo' => 'ezcWorkflowTestVariableHandler' )
        );

        $this->assertTrue( $this->workflow->removeVariableHandler( 'foo' ) );
    }

    /**
     * @expectedException ezcWorkflowInvalidWorkflowException
     */
    public function testVariableHandler2()
    {
        $this->setUpStartEnd();

        $this->workflow->addVariableHandler( 'foo', 'StdClass' );
    }

    /**
     * @expectedException ezcWorkflowInvalidWorkflowException
     */
    public function testVariableHandler3()
    {
        $this->setUpStartEnd();

        $this->workflow->addVariableHandler( 'foo', 'NotExisting' );
    }

    public function testProperties()
    {
        $this->setUpStartEnd();

        $this->assertTrue( isset( $this->workflow->definitionStorage ) );
        $this->assertTrue( isset( $this->workflow->id ) );
        $this->assertTrue( isset( $this->workflow->name ) );
        $this->assertTrue( isset( $this->workflow->nodes ) );
        $this->assertTrue( isset( $this->workflow->version ) );
        $this->assertFalse( isset( $this->workflow->foo ) );
    }

    /**
     * @expectedException ezcBasePropertyNotFoundException
     */
    public function testProperties2()
    {
        $this->setUpStartEnd();

        $foo = $this->workflow->foo;
    }

    /**
     * @expectedException ezcBasePropertyNotFoundException
     */
    public function testProperties3()
    {
        $this->setUpStartEnd();

        $this->workflow->foo = null;
    }

    /**
     * @expectedException ezcBaseValueException
     */
    public function testProperties4()
    {
        $this->setUpStartEnd();

        $this->workflow->definitionStorage = null;
    }

    /**
     * @expectedException ezcBaseValueException
     */
    public function testProperties5()
    {
        $this->setUpStartEnd();

        $this->workflow->id = null;
    }

    /**
     * @expectedException ezcBaseValueException
     */
    public function testProperties6()
    {
        $this->setUpStartEnd();

        $this->workflow->name = null;
    }

    /**
     * @expectedException ezcBasePropertyPermissionException
     */
    public function testProperties7()
    {
        $this->setUpStartEnd();

        $this->workflow->nodes = null;
    }

    /**
     * @expectedException ezcBaseValueException
     */
    public function testProperties8()
    {
        $this->setUpStartEnd();

        $this->workflow->version = null;
    }
}
?>
